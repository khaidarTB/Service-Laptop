<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Service;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $customer = Customer::where('user_id', $user->id)
            ->orWhere('whatsapp', $user->email)
            ->first();

        $services = collect();
        if ($customer) {
            $services = Service::where('customer_id', $customer->id)
                ->with(['technician', 'details.sparepart', 'statusLogs', 'photos', 'transaction'])
                ->latest()
                ->get();
        }

        $activeServices = $services->whereIn('status', ['antrean', 'pemeriksaan', 'menunggu_persetujuan', 'perbaikan']);
        $completedServices = $services->whereIn('status', ['selesai', 'diambil']);

        return view('customer.dashboard', compact('customer', 'services', 'activeServices', 'completedServices'));
    }
}
