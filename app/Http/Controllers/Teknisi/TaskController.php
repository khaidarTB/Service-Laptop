<?php

namespace App\Http\Controllers\Teknisi;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Sparepart;
use App\Models\ServiceDetail;
use App\Models\ServiceStatusLog;
use App\Models\ServicePhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $technicianId = auth()->id();
        $status = $request->query('status');
        $search = $request->query('search');

        $tasks = Service::where('assigned_technician_id', $technicianId)
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($search, function ($query, $search) {
                $query->where(function($q) use ($search) {
                    $q->where('ticket_number', 'like', "%{$search}%")
                      ->orWhere('laptop_brand', 'like', "%{$search}%")
                      ->orWhere('laptop_type', 'like', "%{$search}%");
                });
            })
            ->with(['customer', 'details', 'photos', 'comments'])
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('teknisi.tasks.index', compact('tasks', 'status', 'search'));
    }

    public function show(Service $task)
    {
        if ($task->assigned_technician_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke tiket servis ini.');
        }

        $task->load(['customer', 'details.sparepart', 'statusLogs.user', 'photos', 'comments.user', 'latestApproval']);
        $spareparts = Sparepart::where('stock', '>', 0)->get();

        return view('teknisi.tasks.show', compact('task', 'spareparts'));
    }

    public function edit(Service $task)
    {
        if ($task->assigned_technician_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke tiket servis ini.');
        }

        return view('teknisi.tasks.edit', compact('task'));
    }

    public function update(Request $request, Service $task)
    {
        if ($task->assigned_technician_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke tiket servis ini.');
        }

        $request->validate([
            'diagnosis' => 'required|string',
            'estimated_cost' => 'nullable|numeric|min:0',
            'service_fee' => 'nullable|numeric|min:0',
            'estimated_finish' => 'nullable|date',
        ]);

        $sparepartTotal = $task->details->sum('subtotal');
        $serviceFee = $request->service_fee ?? $task->service_fee;

        $task->update([
            'diagnosis' => $request->diagnosis,
            'estimated_cost' => $request->estimated_cost ?? $task->estimated_cost,
            'service_fee' => $serviceFee,
            'total_cost' => $serviceFee + $sparepartTotal,
            'estimated_finish' => $request->estimated_finish,
        ]);

        return redirect()->route('teknisi.tasks.show', $task->id)
            ->with('success', 'Diagnosa dan Estimasi Servis berhasil disimpan!');
    }

    public function updateStatus(Request $request, Service $task)
    {
        if ($task->assigned_technician_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke tiket servis ini.');
        }

        $request->validate([
            'status' => 'required|in:antrean,pemeriksaan,menunggu_persetujuan,perbaikan,selesai,diambil,batal',
            'notes' => 'nullable|string',
        ]);

        $oldStatus = $task->status;
        $newStatus = $request->status;

        if ($oldStatus === $newStatus) {
            return redirect()->back()->with('error', 'Status tidak berubah.');
        }

        DB::transaction(function() use ($task, $newStatus, $request) {
            $data = ['status' => $newStatus];
            if ($newStatus === 'selesai' || $newStatus === 'diambil') {
                $data['date_completed'] = Carbon::now();
            }

            $task->update($data);

            ServiceStatusLog::create([
                'service_id' => $task->id,
                'old_status' => $task->getOriginal('status'),
                'new_status' => $newStatus,
                'notes' => $request->notes ?? ('Status diperbarui oleh Teknisi ' . auth()->user()->name),
                'changed_by' => auth()->id(),
            ]);
        });

        return redirect()->route('teknisi.tasks.show', $task->id)
            ->with('success', 'Status berhasil diperbarui menjadi ' . $task->status_label . '!');
    }

    public function addSparepart(Request $request, Service $task)
    {
        if ($task->assigned_technician_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'sparepart_id' => 'required|exists:spareparts,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $sparepart = Sparepart::findOrFail($request->sparepart_id);

        if ($sparepart->stock < $request->quantity) {
            return redirect()->back()->with('error', 'Stok sparepart ' . $sparepart->part_name . ' tidak mencukupi (tersedia: ' . $sparepart->stock . ').');
        }

        DB::transaction(function() use ($task, $sparepart, $request) {
            $subtotal = $sparepart->selling_price * $request->quantity;

            $sparepart->decrement('stock', $request->quantity);

            $existing = ServiceDetail::where('service_id', $task->id)
                ->where('sparepart_id', $sparepart->id)
                ->first();

            if ($existing) {
                $existing->increment('quantity', $request->quantity);
                $existing->update(['subtotal' => $existing->quantity * $existing->price_at_time]);
            } else {
                ServiceDetail::create([
                    'service_id' => $task->id,
                    'sparepart_id' => $sparepart->id,
                    'quantity' => $request->quantity,
                    'price_at_time' => $sparepart->selling_price,
                    'subtotal' => $subtotal,
                ]);
            }

            $sparepartSum = ServiceDetail::where('service_id', $task->id)->sum('subtotal');
            $task->update(['total_cost' => $task->service_fee + $sparepartSum]);
        });

        return redirect()->back()->with('success', 'Sparepart ' . $sparepart->part_name . ' berhasil ditambahkan!');
    }

    public function removeSparepart(Service $task, ServiceDetail $detail)
    {
        if ($task->assigned_technician_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        DB::transaction(function() use ($task, $detail) {
            $sparepart = $detail->sparepart;
            if ($sparepart) {
                $sparepart->increment('stock', $detail->quantity);
            }
            $detail->delete();

            $sparepartSum = ServiceDetail::where('service_id', $task->id)->sum('subtotal');
            $task->update(['total_cost' => $task->service_fee + $sparepartSum]);
        });

        return redirect()->back()->with('success', 'Sparepart berhasil dihapus!');
    }

    public function uploadPhoto(Request $request, Service $task)
    {
        if ($task->assigned_technician_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

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
            return redirect()->back()->with('error', 'Silakan unggah foto atau gunakan URL foto.');
        }

        ServicePhoto::create([
            'service_id' => $task->id,
            'image' => $imagePath,
            'description' => $request->description,
            'photo_type' => $request->photo_type,
        ]);

        return redirect()->back()->with('success', 'Foto dokumentasi berhasil diunggah!');
    }

    public function deletePhoto(Service $task, ServicePhoto $photo)
    {
        if ($task->assigned_technician_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        $photo->delete();
        return redirect()->back()->with('success', 'Foto dokumentasi berhasil dihapus!');
    }
}
