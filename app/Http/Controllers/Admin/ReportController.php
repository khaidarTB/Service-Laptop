<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Transaction;
use App\Models\Sparepart;
use App\Models\ServiceDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->query('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->query('end_date', Carbon::now()->endOfMonth()->toDateString());

        // Transactions & Revenue
        $transactions = Transaction::with(['service.customer'])
            ->whereBetween('transaction_date', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->get();

        $totalIncome = $transactions->where('payment_status', 'Lunas')->sum('amount_paid');
        $totalServices = Service::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])->count();
        $completedServices = Service::where('status', 'selesai')
            ->whereBetween('date_completed', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->count();

        // Top Serviced Laptop Brands
        $topBrands = Service::select('laptop_brand', DB::raw('count(*) as total'))
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->groupBy('laptop_brand')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        // Top Spareparts Used
        $topSpareparts = ServiceDetail::select('sparepart_id', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(subtotal) as total_amount'))
            ->with('sparepart')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->groupBy('sparepart_id')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        // Top Technicians
        $topTechnicians = User::where('role', 'teknisi')
            ->withCount(['assignedServices' => function($query) use ($startDate, $endDate) {
                $query->where('status', 'selesai')
                      ->whereBetween('date_completed', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            }])
            ->orderByDesc('assigned_services_count')
            ->get();

        return view('admin.reports.index', compact(
            'startDate',
            'endDate',
            'transactions',
            'totalIncome',
            'totalServices',
            'completedServices',
            'topBrands',
            'topSpareparts',
            'topTechnicians'
        ));
    }

    public function pdf(Request $request)
    {
        $startDate = $request->query('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->query('end_date', Carbon::now()->endOfMonth()->toDateString());

        $transactions = Transaction::with(['service.customer'])
            ->whereBetween('transaction_date', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->get();

        $totalIncome = $transactions->where('payment_status', 'Lunas')->sum('amount_paid');

        return view('admin.reports.pdf', compact('startDate', 'endDate', 'transactions', 'totalIncome'));
    }

    public function excel(Request $request)
    {
        $startDate = $request->query('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->query('end_date', Carbon::now()->endOfMonth()->toDateString());

        $transactions = Transaction::with(['service.customer'])
            ->whereBetween('transaction_date', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->get();

        $filename = "Laporan_Pendapatan_{$startDate}_sd_{$endDate}.csv";

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename={$filename}",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use ($transactions) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['No Invoice', 'No Tiket', 'Pelanggan', 'Metode Bayar', 'Status Bayar', 'Tanggal', 'Jumlah Pembayaran']);

            foreach ($transactions as $t) {
                fputcsv($file, [
                    $t->invoice_number,
                    $t->service ? $t->service->ticket_number : '-',
                    $t->service && $t->service->customer ? $t->service->customer->name : '-',
                    $t->payment_method,
                    $t->payment_status,
                    $t->transaction_date ? $t->transaction_date->format('Y-m-d H:i') : '-',
                    $t->amount_paid
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
