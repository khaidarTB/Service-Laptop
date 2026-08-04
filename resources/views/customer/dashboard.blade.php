<x-app-layout>
    <x-slot name="header">Portal Layanan & Servis Laptop Saya</x-slot>

    <div class="space-y-8">
        <!-- Customer Greeting Banner -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white p-8 rounded-3xl shadow-xl flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div class="space-y-2">
                <h2 class="text-2xl font-black">Selamat Datang, {{ auth()->user()->name }}! 👋</h2>
                <p class="text-xs sm:text-sm text-blue-100 max-w-xl">
                    Pantau status perbaikan laptop Anda secara real-time dari portal ini. Anda juga dapat melacak menggunakan Nomor Tiket Servis.
                </p>
            </div>

            <a href="{{ route('customer.trackForm') }}" class="bg-white text-blue-600 hover:bg-blue-50 font-extrabold px-6 py-3 rounded-2xl text-xs transition shadow-lg flex items-center gap-2">
                <i class="fas fa-magnifying-glass"></i> Lacak Tiket Lain
            </a>
        </div>

        <!-- Active Services List -->
        <div class="space-y-6">
            <h3 class="font-black text-slate-900 text-xl tracking-tight flex items-center gap-2">
                <i class="fas fa-laptop-medical text-blue-600"></i> Unit Laptop Dalam Perbaikan ({{ $activeServices->count() }})
            </h3>

            @forelse($activeServices as $s)
                <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs space-y-6">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-slate-100 pb-4 gap-4">
                        <div>
                            <span class="text-xs font-bold text-blue-600 uppercase tracking-widest bg-blue-50 px-3 py-1 rounded-lg border border-blue-200">
                                Tiket: {{ $s->ticket_number }}
                            </span>
                            <h4 class="text-xl font-black text-slate-900 mt-2">{{ $s->laptop_brand }} {{ $s->laptop_type }}</h4>
                            <p class="text-xs text-slate-400">Masuk Workshop: {{ $s->date_received ? $s->date_received->format('d M Y H:i') : '-' }}</p>
                        </div>

                        <div class="text-left sm:text-right">
                            <span class="{{ $s->status_badge_class }} px-4 py-1.5 rounded-2xl text-xs font-extrabold shadow-xs border inline-block">
                                Status: {{ $s->status_label }}
                            </span>
                            <p class="text-xs font-black text-blue-600 mt-2">
                                Est. Total Biaya: Rp {{ number_format($s->total_cost, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    <!-- Progress Step Timeline Bar -->
                    @php
                        $statusSteps = [
                            'antrean' => ['label' => 'Masuk Servis', 'icon' => 'fa-inbox'],
                            'pemeriksaan' => ['label' => 'Pemeriksaan / Diagnosa', 'icon' => 'fa-magnifying-glass'],
                            'menunggu_persetujuan' => ['label' => 'Menunggu Persetujuan', 'icon' => 'fa-clock'],
                            'perbaikan' => ['label' => 'Proses Perbaikan', 'icon' => 'fa-screwdriver-wrench'],
                            'selesai' => ['label' => 'Servis Selesai', 'icon' => 'fa-circle-check'],
                            'diambil' => ['label' => 'Sudah Diambil', 'icon' => 'fa-box-check']
                        ];
                        $orderKeys = array_keys($statusSteps);
                        $currentIndex = array_search($s->status, $orderKeys);
                        if ($currentIndex === false) $currentIndex = 0;
                    @endphp

                    <div class="relative flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-l-2 md:border-l-0 md:border-t-2 border-slate-200 pl-4 md:pl-0 md:pt-6">
                        @foreach($statusSteps as $key => $step)
                            @php
                                $stepIndex = array_search($key, $orderKeys);
                                $isPassed = $stepIndex <= $currentIndex && $s->status !== 'batal';
                                $isCurrent = $s->status === $key;
                            @endphp
                            <div class="relative flex md:flex-col items-center gap-3 md:gap-2 text-left md:text-center flex-1">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center font-bold text-xs z-10 transition duration-300 {{ $isCurrent ? 'bg-blue-600 text-white ring-4 ring-blue-100 shadow-lg shadow-blue-600/30' : ($isPassed ? 'bg-emerald-500 text-white' : 'bg-slate-100 text-slate-400') }}">
                                    <i class="fas {{ $step['icon'] }}"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-xs {{ $isCurrent ? 'text-blue-600 font-extrabold' : ($isPassed ? 'text-slate-900' : 'text-slate-400') }}">
                                        {{ $step['label'] }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs pt-4 border-t border-slate-100">
                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 space-y-1">
                            <span class="font-bold text-slate-400 uppercase">Keluhan Kerusakan:</span>
                            <p class="font-semibold text-slate-800">{{ $s->complaint }}</p>
                        </div>
                        <div class="bg-blue-50/60 p-4 rounded-2xl border border-blue-100 space-y-1">
                            <span class="font-bold text-blue-600 uppercase">Diagnosa Teknisi:</span>
                            <p class="font-semibold text-slate-800">{{ $s->diagnosis ?? 'Teknisi sedang melakukan pemeriksaan fisik & komponen.' }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white p-12 rounded-3xl border border-slate-200/80 text-center text-slate-400">
                    Tidak ada perbaikan laptop aktif saat ini.
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
