<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function trackForm()
    {
        return view('customer.track');
    }

    public function track(Request $request)
    {
        $request->validate([
            'ticket_number' => 'required|string',
        ]);

        $query = trim($request->ticket_number);

        $service = Service::where('ticket_number', $query)
            ->orWhereHas('customer', function($q) use ($query) {
                $q->where('whatsapp', $query);
            })
            ->with(['customer', 'technician', 'details.sparepart', 'statusLogs.user', 'photos', 'transaction'])
            ->latest()
            ->first();

        if (!$service) {
            return redirect()->route('customer.trackForm')
                ->with('error', 'Nomor Tiket "' . $query . '" tidak ditemukan. Silakan periksa kembali nomor tiket atau nomor WhatsApp Anda.');
        }

        return view('customer.track_result', compact('service'));
    }

    public function show(Service $service)
    {
        $service->load(['customer', 'technician', 'details.sparepart', 'statusLogs.user', 'photos', 'transaction']);
        return view('customer.track_result', compact('service'));
    }
}
