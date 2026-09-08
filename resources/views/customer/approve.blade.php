<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Persetujuan Servis Tiket {{ $service->ticket_number }} - LaptopCare</title>
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
    <main class="max-w-5xl mx-auto p-4 sm:p-6 lg:p-8 space-y-8 my-6" x-data="{ approveModal: false, rejectModal: false }">

        {{-- Flash Messages --}}
        @if(session('error'))
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 text-xs sm:text-sm flex items-start gap-3 text-left">
                <i class="fas fa-circle-exclamation text-rose-600 text-base mt-0.5"></i>
                <div>
                    <h4 class="font-bold">Perhatian!</h4>
                    <p class="mt-0.5">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs sm:text-sm flex items-start gap-3 text-left">
                <i class="fas fa-circle-check text-emerald-600 text-base mt-0.5"></i>
                <div>
                    <h4 class="font-bold">Berhasil!</h4>
                    <p class="mt-0.5">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Header Banner -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div class="space-y-1">
                <div class="flex items-center gap-3 flex-wrap">
                    <span class="text-xs font-bold text-blue-600 bg-blue-50 px-3 py-1 rounded-xl border border-blue-200">
                        TIKET SERVIS: {{ $service->ticket_number }}
                    </span>
                    <span class="text-xs font-bold text-amber-600 bg-amber-50 px-3 py-1 rounded-xl border border-amber-200">
                        <i class="fas fa-clock mr-1"></i>Menunggu Persetujuan
                    </span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 mt-2">{{ $service->laptop_brand }} {{ $service->laptop_type }}</h1>
                <p class="text-xs text-slate-400">Atas Nama: <b>{{ $service->customer?->name ?? '-' }}</b> • Tanggal Masuk: {{ $service->date_received?->format('d M Y H:i') ?? '-' }}</p>
            </div>
            <div class="text-left md:text-right space-y-1">
                <span class="text-xs text-slate-400 font-bold uppercase tracking-wider block">Total Est. Biaya:</span>
                <span class="text-2xl font-black text-blue-600 block">Rp {{ number_format($service->total_cost, 0, ',', '.') }}</span>
            </div>
        </div>

        @if($service->latestApproval)
            {{-- Decision Already Made --}}
            <div class="bg-white p-8 sm:p-12 rounded-3xl border border-slate-200/80 shadow-xs text-center space-y-4">
                <div class="w-20 h-20 rounded-3xl flex items-center justify-center mx-auto text-3xl {{ $service->latestApproval->decision === 'approved' ? 'bg-emerald-50 text-emerald-600 border border-emerald-200' : 'bg-rose-50 text-rose-600 border border-rose-200' }}">
                    <i class="fas {{ $service->latestApproval->decision === 'approved' ? 'fa-circle-check' : 'fa-circle-xmark' }}"></i>
                </div>
                <h2 class="text-xl font-black text-slate-900">Keputusan Sudah Diberikan</h2>
                <p class="text-sm text-slate-500 max-w-md mx-auto">
                    Anda telah memilih <b class="{{ $service->latestApproval->decision === 'approved' ? 'text-emerald-600' : 'text-rose-600' }}">
                        {{ $service->latestApproval->decision === 'approved' ? 'Menyetujui' : 'Menolak' }}
                    </b> perbaikan untuk tiket ini.
                </p>
                @if($service->latestApproval->note)
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 max-w-md mx-auto text-left">
                        <span class="text-[11px] font-bold text-slate-400 uppercase">Catatan Anda:</span>
                        <p class="text-sm text-slate-700 font-medium mt-1">{{ $service->latestApproval->note }}</p>
                    </div>
                @endif
                <a href="{{ route('customer.services.show', $service->id) }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-extrabold px-6 py-3 rounded-2xl text-sm transition shadow-xl shadow-blue-600/30 mt-4">
                    <i class="fas fa-arrow-right"></i> Lihat Status Servis
                </a>
            </div>
        @else
            {{-- Decision Area --}}
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

                <!-- Cost Breakdown -->
                <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs space-y-4">
                    <h3 class="font-extrabold text-slate-900 text-base border-b border-slate-100 pb-3 flex items-center gap-2">
                        <i class="fas fa-receipt text-blue-600"></i> Rincian Biaya
                    </h3>
                    <div class="divide-y divide-slate-100 text-xs">
                        <div class="py-2.5 flex justify-between font-bold text-slate-600">
                            <span>Jasa Perbaikan:</span>
                            <span>Rp {{ number_format($service->service_fee, 0, ',', '.') }}</span>
                        </div>
                        @forelse($service->details as $detail)
                            <div class="py-2.5 flex justify-between text-slate-800 font-medium">
                                <span>{{ $detail->sparepart?->part_name ?? 'Part' }} ({{ $detail->quantity }}x)</span>
                                <span class="font-bold text-blue-600">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                            </div>
                        @empty
                            <p class="text-slate-400 py-3 text-center">Belum ada pergantian sparepart.</p>
                        @endforelse
                        <div class="py-3 flex justify-between items-center">
                            <span class="font-black text-sm text-slate-900">Total Biaya:</span>
                            <span class="font-black text-lg text-blue-600">Rp {{ number_format($service->total_cost, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="bg-white p-8 sm:p-10 rounded-3xl border border-slate-200/80 shadow-xs text-center space-y-6">
                <div class="space-y-2">
                    <h2 class="text-xl font-black text-slate-900">Ajukan Keputusan Anda</h2>
                    <p class="text-xs sm:text-sm text-slate-500">Pastikan Anda sudah meninjau rincian biaya dan diagnosa teknisi sebelum mengambil keputusan.</p>
                </div>

                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    {{-- Approve Button --}}
                    <button @click="approveModal = true" class="group flex-1 max-w-xs mx-auto sm:mx-0 px-8 py-5 bg-emerald-500 hover:bg-emerald-600 text-white font-extrabold rounded-2xl text-sm transition shadow-xl shadow-emerald-500/30 flex items-center justify-center gap-3">
                        <i class="fas fa-circle-check text-lg"></i>
                        <span>Setujui & Lanjutkan Perbaikan</span>
                    </button>

                    {{-- Reject Button --}}
                    <button @click="rejectModal = true" class="group flex-1 max-w-xs mx-auto sm:mx-0 px-8 py-5 bg-rose-500 hover:bg-rose-600 text-white font-extrabold rounded-2xl text-sm transition shadow-xl shadow-rose-500/30 flex items-center justify-center gap-3">
                        <i class="fas fa-circle-xmark text-lg"></i>
                        <span>Tolak & Batalkan</span>
                    </button>
                </div>
            </div>

            {{-- ========== APPROVE MODAL ========== --}}
            <div x-show="approveModal" x-cloak x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="approveModal = false"></div>
                <div x-show="approveModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-95 translate-y-4" class="relative bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-2xl w-full max-w-md space-y-6 z-10">
                    <div class="text-center space-y-2">
                        <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mx-auto text-xl border border-emerald-200">
                            <i class="fas fa-circle-check"></i>
                        </div>
                        <h3 class="text-lg font-black text-slate-900">Setujui Perbaikan?</h3>
                        <p class="text-xs text-slate-500">Anda menyetujui perbaikan dengan total biaya <b class="text-blue-600">Rp {{ number_format($service->total_cost, 0, ',', '.') }}</b>. Teknisi akan segera mulai bekerja.</p>
                    </div>
                    <form action="{{ route('customer.services.approve', $service->id) }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="decision" value="approved">
                        <div>
                            <label class="text-xs font-bold text-slate-600 mb-1.5 block">Catatan (Opsional)</label>
                            <textarea name="note" rows="3" placeholder="Contoh: Mohon segera dikerjakan, urgent..." class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-xs text-slate-800 placeholder:text-slate-400 bg-slate-50 transition resize-none"></textarea>
                        </div>
                        <div class="flex gap-3">
                            <button type="button" @click="approveModal = false" class="flex-1 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-extrabold rounded-2xl text-xs transition">Batal</button>
                            <button type="submit" class="flex-1 py-3 bg-emerald-500 hover:bg-emerald-600 text-white font-extrabold rounded-2xl text-xs transition shadow-lg shadow-emerald-500/30 flex items-center justify-center gap-2">
                                <i class="fas fa-check"></i> Ya, Setujui
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- ========== REJECT MODAL ========== --}}
            <div x-show="rejectModal" x-cloak x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="rejectModal = false"></div>
                <div x-show="rejectModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-95 translate-y-4" class="relative bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-2xl w-full max-w-md space-y-6 z-10">
                    <div class="text-center space-y-2">
                        <div class="w-14 h-14 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center mx-auto text-xl border border-rose-200">
                            <i class="fas fa-circle-xmark"></i>
                        </div>
                        <h3 class="text-lg font-black text-slate-900">Tolak Perbaikan?</h3>
                        <p class="text-xs text-slate-500">Perbaikan untuk tiket ini akan dibatalkan. Silakan ambil unit Anda di workshop.</p>
                    </div>
                    <form action="{{ route('customer.services.approve', $service->id) }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="decision" value="rejected">
                        <div>
                            <label class="text-xs font-bold text-slate-600 mb-1.5 block">Alasan Penolakan (Opsional)</label>
                            <textarea name="note" rows="3" placeholder="Contoh: Biaya terlalu mahal, saya urungkan..." class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-xs text-slate-800 placeholder:text-slate-400 bg-slate-50 transition resize-none"></textarea>
                        </div>
                        <div class="flex gap-3">
                            <button type="button" @click="rejectModal = false" class="flex-1 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-extrabold rounded-2xl text-xs transition">Batal</button>
                            <button type="submit" class="flex-1 py-3 bg-rose-500 hover:bg-rose-600 text-white font-extrabold rounded-2xl text-xs transition shadow-lg shadow-rose-500/30 flex items-center justify-center gap-2">
                                <i class="fas fa-xmark"></i> Ya, Tolak
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

    </main>

    <footer class="py-8 text-center text-xs text-slate-400 border-t border-slate-200">
        &copy; {{ date('Y') }} LaptopCare Service Center. All rights reserved.
    </footer>

</body>
</html>
