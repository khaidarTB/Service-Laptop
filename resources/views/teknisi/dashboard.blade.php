<x-app-layout>
    <x-slot name="header">Dashboard Teknisi Workbench</x-slot>

    <div class="space-y-8">
        <!-- Overview Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs space-y-2">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Servis Aktif Dikerjakan</span>
                <p class="text-3xl font-black text-blue-600">{{ $activeTasks->count() }} Unit</p>
                <p class="text-xs text-slate-500">Tugas aktif Anda hari ini</p>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs space-y-2">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Perlu Diagnosa</span>
                <p class="text-3xl font-black text-amber-600">{{ $pendingDiagnosisCount }} Unit</p>
                <p class="text-xs text-amber-600 font-medium">Status: Pemeriksaan</p>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs space-y-2">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Servis Selesai Dikirim</span>
                <p class="text-3xl font-black text-emerald-600">{{ $completedTasksCount }} Unit</p>
                <p class="text-xs text-emerald-600 font-medium">Telah selesai Anda perbaiki</p>
            </div>
        </div>

        <!-- Active Task Cards Grid -->
        <div class="space-y-4">
            <div class="flex justify-between items-center">
                <h3 class="font-bold text-slate-900 text-lg">Daftar Pekerjaan Aktif</h3>
                <a href="{{ route('teknisi.tasks.index') }}" class="text-xs font-bold text-blue-600 hover:underline">Lihat Semua Task</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($activeTasks as $task)
                    <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs space-y-4 flex flex-col justify-between hover:border-blue-300 transition group">
                        <div class="space-y-3">
                            <div class="flex justify-between items-start">
                                <span class="font-extrabold text-blue-600 text-xs bg-blue-50 px-3 py-1 rounded-xl border border-blue-100">
                                    {{ $task->ticket_number }}
                                </span>
                                <span class="{{ $task->status_badge_class }} px-2.5 py-0.5 rounded-lg text-[11px] font-extrabold border">
                                    {{ $task->status_label }}
                                </span>
                            </div>
                            <div>
                                <h4 class="font-extrabold text-slate-900 text-base group-hover:text-blue-600 transition">{{ $task->laptop_brand }} {{ $task->laptop_type }}</h4>
                                <p class="text-xs text-slate-500 mt-1 line-clamp-2"><b>Keluhan:</b> {{ $task->complaint }}</p>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-xs text-slate-400 font-medium">Pelanggan: {{ $task->customer ? $task->customer->name : '-' }}</span>
                            <a href="{{ route('teknisi.tasks.show', $task->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-4 py-2 rounded-xl text-xs transition flex items-center gap-1">
                                Kerjakan <i class="fas fa-arrow-right text-[10px]"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white p-12 rounded-3xl border border-slate-200/80 text-center text-slate-400">
                        Tidak ada pengerjaan aktif saat ini.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
