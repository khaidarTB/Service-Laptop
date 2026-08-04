<?php

namespace App\Http\Controllers\Teknisi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;

class DashboardController extends Controller
{
    public function index()
    {
        $technicianId = auth()->id();

        $activeTasks = Service::where('assigned_technician_id', $technicianId)
            ->whereIn('status', ['antrean', 'pemeriksaan', 'menunggu_persetujuan', 'perbaikan'])
            ->with(['customer', 'photos', 'details'])
            ->orderBy('id', 'desc')
            ->get();

        $completedTasksCount = Service::where('assigned_technician_id', $technicianId)
            ->where('status', 'selesai')
            ->count();

        $pendingDiagnosisCount = Service::where('assigned_technician_id', $technicianId)
            ->where('status', 'pemeriksaan')
            ->count();

        return view('teknisi.dashboard', compact('activeTasks', 'completedTasksCount', 'pendingDiagnosisCount'));
    }
}
