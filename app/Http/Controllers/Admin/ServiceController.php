<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Customer;
use App\Models\User;
use App\Models\Sparepart;
use App\Models\ServiceDetail;
use App\Models\ServiceStatusLog;
use App\Models\ServicePhoto;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $search = $request->query('search');

        $services = Service::with(['customer', 'technician', 'transaction'])
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($search, function ($query, $search) {
                $query->where(function($q) use ($search) {
                    $q->where('ticket_number', 'like', "%{$search}%")
                      ->orWhere('laptop_brand', 'like', "%{$search}%")
                      ->orWhere('laptop_type', 'like', "%{$search}%")
                      ->orWhere('serial_number', 'like', "%{$search}%")
                      ->orWhereHas('customer', function($c) use ($search) {
                          $c->where('name', 'like', "%{$search}%")
                            ->orWhere('whatsapp', 'like', "%{$search}%");
                      });
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $statuses = ['antrean', 'pemeriksaan', 'menunggu_persetujuan', 'perbaikan', 'selesai', 'diambil', 'batal'];

        return view('admin.services.index', compact('services', 'status', 'search', 'statuses'));
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $technicians = User::where('role', 'teknisi')->orderBy('name')->get();
        $spareparts = Sparepart::where('stock', '>', 0)->get();
        $ticketNumber = Service::generateTicketNumber();

        return view('admin.services.create', compact('customers', 'technicians', 'spareparts', 'ticketNumber'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'assigned_technician_id' => 'nullable|exists:users,id',
            'laptop_brand' => 'required|string|max:100',
            'laptop_type' => 'required|string|max:100',
            'serial_number' => 'nullable|string|max:100',
            'equipment' => 'nullable|string',
            'complaint' => 'required|string',
            'diagnosis' => 'nullable|string',
            'service_fee' => 'nullable|numeric|min:0',
            'estimated_cost' => 'nullable|numeric|min:0',
            'estimated_finish' => 'nullable|date',
            'status' => 'required|in:antrean,pemeriksaan,menunggu_persetujuan,perbaikan,selesai,diambil,batal',
        ]);

        try {
            DB::beginTransaction();

            $service = Service::create([
                'ticket_number' => Service::generateTicketNumber(),
                'customer_id' => $request->customer_id,
                'assigned_technician_id' => $request->assigned_technician_id,
                'laptop_brand' => $request->laptop_brand,
                'laptop_type' => $request->laptop_type,
                'serial_number' => $request->serial_number,
                'equipment' => $request->equipment,
                'complaint' => $request->complaint,
                'diagnosis' => $request->diagnosis,
                'service_fee' => $request->service_fee ?? 0,
                'total_cost' => $request->service_fee ?? 0,
                'estimated_cost' => $request->estimated_cost ?? 0,
                'estimated_finish' => $request->estimated_finish,
                'status' => $request->status,
                'date_received' => Carbon::now(),
            ]);

            ServiceStatusLog::create([
                'service_id' => $service->id,
                'old_status' => null,
                'new_status' => $request->status,
                'notes' => 'Tiket servis dibuat oleh Admin.',
                'changed_by' => auth()->id(),
            ]);

            DB::commit();

            return redirect()->route('admin.services.show', $service->id)
                ->with('success', 'Data Servis dengan Tiket ' . $service->ticket_number . ' berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(Service $service)
    {
        $service->load(['customer', 'technician', 'details.sparepart', 'statusLogs.user', 'photos', 'transaction']);
        $technicians = User::where('role', 'teknisi')->orderBy('name')->get();
        $spareparts = Sparepart::where('stock', '>', 0)->get();

        return view('admin.services.show', compact('service', 'technicians', 'spareparts'));
    }

    public function edit(Service $service)
    {
        $customers = Customer::orderBy('name')->get();
        $technicians = User::where('role', 'teknisi')->orderBy('name')->get();
        return view('admin.services.edit', compact('service', 'customers', 'technicians'));
    }

    public function update(Request $request, Service $service)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'assigned_technician_id' => 'nullable|exists:users,id',
            'laptop_brand' => 'required|string|max:100',
            'laptop_type' => 'required|string|max:100',
            'serial_number' => 'nullable|string|max:100',
            'equipment' => 'nullable|string',
            'complaint' => 'required|string',
            'diagnosis' => 'nullable|string',
            'service_fee' => 'nullable|numeric|min:0',
            'estimated_cost' => 'nullable|numeric|min:0',
            'estimated_finish' => 'nullable|date',
        ]);

        $oldFee = $service->service_fee;
        $newFee = $request->service_fee ?? 0;
        $sparepartTotal = $service->details->sum('subtotal');

        $service->update([
            'customer_id' => $request->customer_id,
            'assigned_technician_id' => $request->assigned_technician_id,
            'laptop_brand' => $request->laptop_brand,
            'laptop_type' => $request->laptop_type,
            'serial_number' => $request->serial_number,
            'equipment' => $request->equipment,
            'complaint' => $request->complaint,
            'diagnosis' => $request->diagnosis,
            'service_fee' => $newFee,
            'total_cost' => $newFee + $sparepartTotal,
            'estimated_cost' => $request->estimated_cost ?? 0,
            'estimated_finish' => $request->estimated_finish,
        ]);

        return redirect()->route('admin.services.show', $service->id)->with('success', 'Data Servis berhasil diperbarui!');
    }

    public function updateStatus(Request $request, Service $service)
    {
        $request->validate([
            'status' => 'required|in:antrean,pemeriksaan,menunggu_persetujuan,perbaikan,selesai,diambil,batal',
            'notes' => 'nullable|string',
        ]);

        $oldStatus = $service->status;
        $newStatus = $request->status;

        if ($oldStatus === $newStatus) {
            return redirect()->back()->with('error', 'Status tidak berubah.');
        }

        DB::transaction(function() use ($service, $oldStatus, $newStatus, $request) {
            $data = ['status' => $newStatus];
            if ($newStatus === 'selesai' || $newStatus === 'diambil') {
                $data['date_completed'] = Carbon::now();
            }

            $service->update($data);

            ServiceStatusLog::create([
                'service_id' => $service->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'notes' => $request->notes ?? ('Status diubah menjadi ' . $service->status_label),
                'changed_by' => auth()->id(),
            ]);
        });

        $waUrl = $service->whatsapp_url;
        $notifyTriggers = ['pemeriksaan', 'menunggu_persetujuan', 'perbaikan', 'selesai'];

        if (in_array($newStatus, $notifyTriggers) && $waUrl) {
            return redirect()->route('admin.services.show', $service->id)
                ->with('success', 'Status berhasil diubah menjadi ' . $service->status_label . '!')
                ->with('wa_url', $waUrl);
        }

        return redirect()->route('admin.services.show', $service->id)
            ->with('success', 'Status berhasil diubah menjadi ' . $service->status_label . '!');
    }

    public function addSparepart(Request $request, Service $service)
    {
        $request->validate([
            'sparepart_id' => 'required|exists:spareparts,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $sparepart = Sparepart::findOrFail($request->sparepart_id);

        if ($sparepart->stock < $request->quantity) {
            return redirect()->back()->with('error', 'Stok sparepart ' . $sparepart->part_name . ' tidak mencukupi (tersedia: ' . $sparepart->stock . ').');
        }

        DB::transaction(function() use ($service, $sparepart, $request) {
            $subtotal = $sparepart->selling_price * $request->quantity;

            // Reduce stock
            $sparepart->decrement('stock', $request->quantity);

            // Add or update detail
            $existing = ServiceDetail::where('service_id', $service->id)
                ->where('sparepart_id', $sparepart->id)
                ->first();

            if ($existing) {
                $existing->increment('quantity', $request->quantity);
                $existing->update(['subtotal' => $existing->quantity * $existing->price_at_time]);
            } else {
                ServiceDetail::create([
                    'service_id' => $service->id,
                    'sparepart_id' => $sparepart->id,
                    'quantity' => $request->quantity,
                    'price_at_time' => $sparepart->selling_price,
                    'subtotal' => $subtotal,
                ]);
            }

            // Recalculate total cost
            $sparepartSum = ServiceDetail::where('service_id', $service->id)->sum('subtotal');
            $service->update(['total_cost' => $service->service_fee + $sparepartSum]);
        });

        return redirect()->back()->with('success', 'Sparepart ' . $sparepart->part_name . ' berhasil ditambahkan ke keranjang servis!');
    }

    public function removeSparepart(Service $service, ServiceDetail $detail)
    {
        DB::transaction(function() use ($service, $detail) {
            $sparepart = $detail->sparepart;
            if ($sparepart) {
                $sparepart->increment('stock', $detail->quantity);
            }
            $detail->delete();

            $sparepartSum = ServiceDetail::where('service_id', $service->id)->sum('subtotal');
            $service->update(['total_cost' => $service->service_fee + $sparepartSum]);
        });

        return redirect()->back()->with('success', 'Sparepart berhasil dihapus dari daftar servis!');
    }

    public function uploadPhoto(Request $request, Service $service)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'image_url' => 'nullable|url',
            'description' => 'nullable|string',
            'photo_type' => 'required|in:before,after',
        ]);

        $imagePath = $request->image_url;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('service_photos', 'public');
            $imagePath = Storage::url($path);
        }

        if (!$imagePath) {
            return redirect()->back()->with('error', 'Silakan pilih berkas foto atau masukkan URL foto.');
        }

        ServicePhoto::create([
            'service_id' => $service->id,
            'image' => $imagePath,
            'description' => $request->description,
            'photo_type' => $request->photo_type,
        ]);

        return redirect()->back()->with('success', 'Foto dokumentasi servis berhasil diunggah!');
    }

    public function deletePhoto(Service $service, ServicePhoto $photo)
    {
        $photo->delete();
        return redirect()->back()->with('success', 'Foto dokumentasi berhasil dihapus!');
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return redirect()->route('admin.services.index')->with('success', 'Data Servis berhasil dihapus!');
    }
}
