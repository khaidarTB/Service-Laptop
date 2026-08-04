<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Perbaikan Tiket {{ $service->ticket_number }} - LaptopCare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen">

    <!-- Navbar -->
    <nav class="bg-white/90 backdrop-blur-md sticky top-0 z-40 border-b border-slate-200 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <a href="/" class="text-2xl text-blue-600 font-extrabold tracking-tight flex items-center gap-2">
                <i class="fas fa-laptop-medical"></i> LaptopCare
            </a>
            <div class="flex items-center gap-3">
                <a href="{{ route('customer.trackForm') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold px-4 py-2.5 rounded-2xl text-xs transition">
                    <i class="fas fa-search mr-1"></i> Lacak Tiket Lain
                </a>
            </div>
        </div>
    </nav>

    <!-- Content Container -->
    <main class="max-w-5xl mx-auto p-4 sm:p-6 lg:p-8 space-y-8 my-6">
        
        <!-- Header Banner -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div class="space-y-1">
                <div class="flex items-center gap-3">
                    <span class="text-xs font-bold text-blue-600 bg-blue-50 px-3 py-1 rounded-xl border border-blue-200">
                        TIKET SERVIS: {{ $service->ticket_number }}
                    </span>
                    <span class="{{ $service->status_badge_class }} px-3.5 py-1 rounded-xl text-xs font-extrabold border">
                        {{ $service->status_label }}
                    </span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 mt-2">{{ $service->laptop_brand }} {{ $service->laptop_type }}</h1>
                <p class="text-xs text-slate-400">Atas Nama: <b>{{ $service->customer ? $service->customer->name : '-' }}</b> • Tanggal Masuk: {{ $service->date_received ? $service->date_received->format('d M Y H:i') : '-' }}</p>
            </div>

            <div class="text-left md:text-right space-y-1">
                <span class="text-xs text-slate-400 font-bold uppercase tracking-wider block">Total Est. Biaya:</span>
                <span class="text-2xl font-black text-blue-600 block">Rp {{ number_format($service->total_cost, 0, ',', '.') }}</span>
                @if($service->transaction)
                    <span class="inline-block bg-emerald-50 text-emerald-700 text-[11px] font-extrabold px-2.5 py-0.5 rounded-md border border-emerald-200">
                        Status Faktur: {{ $service->transaction->payment_status }}
                    </span>
                @endif
            </div>
        </div>

        <!-- PROGRESS STEP TIMELINE BAR -->
        <div class="bg-white p-6 sm:p-10 rounded-3xl border border-slate-200/80 shadow-xs space-y-8">
            <div class="text-center max-w-xl mx-auto space-y-1">
                <span class="text-xs font-bold text-blue-600 uppercase tracking-widest bg-blue-50 px-3 py-1 rounded-lg border border-blue-200">Live Workshop Tracker</span>
                <h2 class="text-2xl font-black text-slate-900">Progres Timeline Perbaikan</h2>
            </div>

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
                $currentIndex = array_search($service->status, $orderKeys);
                if ($currentIndex === false) $currentIndex = 0;
            @endphp

            <div class="relative flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-l-2 md:border-l-0 md:border-t-2 border-slate-200 pl-4 md:pl-0 md:pt-8">
                @foreach($statusSteps as $key => $step)
                    @php
                        $stepIndex = array_search($key, $orderKeys);
                        $isPassed = $stepIndex <= $currentIndex && $service->status !== 'batal';
                        $isCurrent = $service->status === $key;
                    @endphp
                    <div class="relative flex md:flex-col items-center gap-3 md:gap-3 text-left md:text-center flex-1">
                        <div class="w-11 h-11 rounded-2xl flex items-center justify-center font-black text-sm z-10 transition duration-300 {{ $isCurrent ? 'bg-blue-600 text-white ring-4 ring-blue-100 shadow-xl shadow-blue-600/30 scale-110' : ($isPassed ? 'bg-emerald-500 text-white shadow-md' : 'bg-slate-100 text-slate-400') }}">
                            <i class="fas {{ $step['icon'] }}"></i>
                        </div>
                        <div>
                            <p class="font-extrabold text-xs {{ $isCurrent ? 'text-blue-600 text-sm' : ($isPassed ? 'text-slate-900' : 'text-slate-400') }}">
                                {{ $step['label'] }}
                            </p>
                            @if($isCurrent)
                                <span class="text-[10px] text-blue-600 bg-blue-50 font-extrabold px-2.5 py-0.5 rounded-full border border-blue-200 inline-block mt-1">Status Saat Ini</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Details Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Diagnosis & Complaints -->
            <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs space-y-4">
                <h3 class="font-extrabold text-slate-900 text-base border-b border-slate-100 pb-3 flex items-center gap-2">
                    <i class="fas fa-stethoscope text-blue-600"></i> Keluhan & Hasil Diagnosa
                </h3>
                <div class="space-y-4 text-xs">
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 space-y-1">
                        <span class="font-bold text-slate-400 uppercase">Keluhan Awal:</span>
                        <p class="font-semibold text-slate-800 leading-relaxed">{{ $service->complaint }}</p>
                    </div>
                    <div class="bg-blue-50/60 p-4 rounded-2xl border border-blue-100 space-y-1">
                        <span class="font-bold text-blue-600 uppercase">Hasil Diagnosa Teknisi:</span>
                        <p class="font-semibold text-slate-800 leading-relaxed">{{ $service->diagnosis ?? 'Teknisi sedang melakukan diagnosa mendalam.' }}</p>
                    </div>
                </div>
            </div>

            <!-- Spareparts Used -->
            <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs space-y-4">
                <h3 class="font-extrabold text-slate-900 text-base border-b border-slate-100 pb-3 flex items-center gap-2">
                    <i class="fas fa-boxes-packing text-blue-600"></i> Sparepart / Part Diganti
                </h3>
                <div class="divide-y divide-slate-100 text-xs">
                    <div class="py-2.5 flex justify-between font-bold text-slate-600">
                        <span>Jasa Perbaikan:</span>
                        <span>Rp {{ number_format($service->service_fee, 0, ',', '.') }}</span>
                    </div>
                    @forelse($service->details as $detail)
                        <div class="py-2.5 flex justify-between text-slate-800 font-medium">
                            <span>{{ $detail->sparepart ? $detail->sparepart->part_name : 'Part' }} ({{ $detail->quantity }}x)</span>
                            <span class="font-bold text-blue-600">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                        </div>
                    @empty
                        <p class="text-slate-400 py-3 text-center">Belum ada pergantian sparepart.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Documentation Photo Gallery -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs space-y-6">
            <h3 class="font-extrabold text-slate-900 text-base border-b border-slate-100 pb-3 flex items-center gap-2">
                <i class="fas fa-camera text-blue-600"></i> Dokumentasi Foto Fisik & Kerusakan Laptop
            </h3>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                @forelse($service->photos as $photo)
                    <div class="relative group rounded-2xl overflow-hidden border border-slate-200 bg-slate-100 aspect-square">
                        <img src="{{ $photo->image }}" class="w-full h-full object-cover group-hover:scale-105 transition">
                        <div class="absolute inset-0 bg-slate-900/60 opacity-0 group-hover:opacity-100 transition p-3 flex flex-col justify-between text-white text-xs">
                            <span class="font-bold capitalize bg-white/20 px-2 py-0.5 rounded-md w-fit">
                                {{ $photo->photo_type === 'before' ? 'Sebelum Perbaikan' : 'Setelah Perbaikan' }}
                            </span>
                            <p class="text-[11px] line-clamp-2">{{ $photo->description }}</p>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-6 text-center text-slate-400 text-xs">Belum ada foto dokumentasi diunggah oleh teknisi.</div>
                @endforelse
            </div>
        </div>

    </main>

    <footer class="py-8 text-center text-xs text-slate-400 border-t border-slate-200">
        &copy; {{ date('Y') }} LaptopCare Service Center. All rights reserved.
    </footer>

</body>
</html>
