<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Service;
use App\Models\Sparepart;
use App\Models\ServiceDetail;
use App\Models\ServiceStatusLog;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LandingController extends Controller
{
    public function index()
    {
        $spareparts = Sparepart::where('stock', '>', 0)->get();
        return view('index', compact('spareparts'));
    }

    public function booking(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'whatsapp' => 'required|string|max:20',
            'address' => 'required|string',
            'laptop_brand' => 'required|string|max:100',
            'laptop_type' => 'required|string|max:100',
            'complaint' => 'required|string',
            'equipment' => 'nullable|string',
            'cart_items' => 'nullable|string', 
        ]);

        try {
            DB::beginTransaction();

            $userId = auth()->check() && auth()->user()->role === 'customer' ? auth()->id() : null;

            // Cari atau buat data Customer berdasarkan user_id / No WhatsApp
            if ($userId) {
                $customer = Customer::where('user_id', $userId)->first();
            } else {
                $customer = Customer::where('whatsapp', $request->whatsapp)->first();
            }

            if ($customer) {
                // Update data customer dan pastikan user_id-nya terikat dengan akun login
                $customer->update([
                    'name' => $request->name,
                    'whatsapp' => $request->whatsapp,
                    'address' => $request->address,
                    'user_id' => $userId ?? $customer->user_id
                ]);
            } else {
                // Buat customer baru jika belum ada
                $customer = Customer::create([
                    'user_id' => $userId,
                    'name' => $request->name,
                    'whatsapp' => $request->whatsapp,
                    'address' => $request->address
                ]);
            }

            $ticketNumber = Service::generateTicketNumber();

            $service = Service::create([
                'ticket_number' => $ticketNumber,
                'customer_id' => $customer->id,
                'laptop_brand' => $request->laptop_brand,
                'laptop_type' => $request->laptop_type,
                'complaint' => $request->complaint,
                'equipment' => $request->equipment,
                'status' => 'antrean',
                'date_received' => Carbon::now(),
            ]);

            ServiceStatusLog::create([
                'service_id' => $service->id,
                'old_status' => null,
                'new_status' => 'antrean',
                'notes' => 'Booking online disubmit oleh pelanggan.',
                'changed_by' => auth()->id(),
            ]);

            if ($request->filled('cart_items')) {
                $cartItems = json_decode($request->cart_items, true);
                if (is_array($cartItems)) {
                    $totalSparepartCost = 0;
                    foreach ($cartItems as $item) {
                        $sparepart = Sparepart::find($item['id']);
                        if ($sparepart && $sparepart->stock >= $item['quantity']) {
                            $subtotal = $sparepart->selling_price * $item['quantity'];
                            ServiceDetail::create([
                                'service_id' => $service->id,
                                'sparepart_id' => $sparepart->id,
                                'quantity' => $item['quantity'],
                                'price_at_time' => $sparepart->selling_price,
                                'subtotal' => $subtotal,
                            ]);
                            $totalSparepartCost += $subtotal;
                        }
                    }
                    if ($totalSparepartCost > 0) {
                        $service->update(['estimated_cost' => $totalSparepartCost]);
                    }
                }
            }

            DB::commit();

            if (auth()->check()) {
                return back()->with('success', 'Booking servis berhasil dikirim! Nomor Tiket Anda: ' . $service->ticket_number);
            }

            return redirect('/#daftar')->with('success', 'Booking berhasil! Nomor Tiket Servis Anda: ' . $service->ticket_number . '. Simpan nomor ini untuk melacak status servis!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}