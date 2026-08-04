<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Transaction;
use App\Models\Sparepart;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        
        $totalServisHariIni = Service::whereDate('date_received', $today)->count();
        $servisDalamProses = Service::whereIn('status', ['antrean', 'pemeriksaan', 'menunggu_persetujuan', 'perbaikan'])->count();
        $servisSelesai = Service::where('status', 'selesai')->count();
        
        $pendapatanHariIni = Transaction::where('payment_status', 'Lunas')
            ->whereDate('transaction_date', $today)
            ->sum('amount_paid');
            
        $pendapatanBulanan = Transaction::where('payment_status', 'Lunas')
            ->whereMonth('transaction_date', Carbon::now()->month)
            ->whereYear('transaction_date', Carbon::now()->year)
            ->sum('amount_paid');
            
        $sparepartHampirHabis = Sparepart::whereColumn('stock', '<=', 'min_stock')->get();
        
        // Teknisi Paling Produktif
        $teknisiProduktif = User::where('role', 'teknisi')
            ->withCount(['assignedServices' => function($query) {
                $query->where('status', 'selesai');
            }])
            ->orderByDesc('assigned_services_count')
            ->take(5)
            ->get();
            
        // Monthly Service Chart Data (Last 6 Months)
        $months = [];
        $serviceChartData = [];
        $revenueChartData = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthName = $date->translatedFormat('M Y');
            $months[] = $monthName;

            $count = Service::whereMonth('date_received', $date->month)
                ->whereYear('date_received', $date->year)
                ->count();
            $serviceChartData[] = $count;

            $revenue = Transaction::where('payment_status', 'Lunas')
                ->whereMonth('transaction_date', $date->month)
                ->whereYear('transaction_date', $date->year)
                ->sum('amount_paid');
            $revenueChartData[] = (float) $revenue;
        }

        $recentServices = Service::with(['customer', 'technician'])
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalServisHariIni',
            'servisDalamProses',
            'servisSelesai',
            'pendapatanHariIni',
            'pendapatanBulanan',
            'sparepartHampirHabis',
            'teknisiProduktif',
            'months',
            'serviceChartData',
            'revenueChartData',
            'recentServices'
        ));
    }
}
