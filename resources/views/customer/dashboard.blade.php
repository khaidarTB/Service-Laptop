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

        <!-- POP-UP NOTIFIKASI BERHASIL SUBMIT BOOKING -->
@if(session('success'))
    <div x-data="{ openSuccessModal: true }" x-show="openSuccessModal" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">
        
        <!-- Background Overlay -->
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="openSuccessModal = false"></div>

        <!-- Card Pop-up Success -->
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-sm p-6 sm:p-8 space-y-5 z-10 text-center"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100">
            
            <!-- Icon Centang Hijau -->
            <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto shadow-lg shadow-emerald-500/20">
                <i class="fas fa-check text-2xl"></i>
            </div>

            <!-- Pesan Berhasil -->
            <div class="space-y-2">
                <h3 class="text-xl font-black text-slate-900">Booking Berhasil!</h3>
                <p class="text-xs text-slate-500 leading-relaxed">
                    {{ session('success') }}
                </p>
            </div>

            <!-- Tombol Tutup -->
            <button @click="openSuccessModal = false"
                class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold py-3 rounded-2xl text-xs transition shadow-lg shadow-emerald-600/30 cursor-pointer">
                Siap, Mengerti!
            </button>
        </div>
    </div>
@endif

<!-- POP-UP NOTIFIKASI ERROR (JIKA TERJADI KESALAHAN) -->
@if(session('error'))
    <div x-data="{ openErrorModal: true }" x-show="openErrorModal" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">
        
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="openErrorModal = false"></div>

        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-sm p-6 sm:p-8 space-y-5 z-10 text-center">
            <div class="w-16 h-16 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center mx-auto shadow-lg shadow-rose-500/20">
                <i class="fas fa-exclamation text-2xl"></i>
            </div>

            <div class="space-y-2">
                <h3 class="text-xl font-black text-slate-900">Gagal Mengirim!</h3>
                <p class="text-xs text-slate-500 leading-relaxed">
                    {{ session('error') }}
                </p>
            </div>

            <button @click="openErrorModal = false"
                class="w-full bg-rose-600 hover:bg-rose-700 text-white font-extrabold py-3 rounded-2xl text-xs transition shadow-lg shadow-rose-600/30 cursor-pointer">
                Tutup
            </button>
        </div>
    </div>
@endif

<!-- Active Services List -->
<div class="space-y-6" x-data="{ openAddModal: false }">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <h3 class="font-black text-slate-900 text-xl tracking-tight flex items-center gap-2">
            <i class="fas fa-laptop-medical text-blue-600"></i> Unit Laptop Dalam Perbaikan ({{ $activeServices->count() }})
        </h3>
        
        <!-- Tombol Buka Pop-up -->
        <button type="button" @click="openAddModal = true" class="bg-blue-600 hover:bg-blue-700 text-white font-extrabold px-5 py-2.5 rounded-2xl text-xs transition shadow-lg shadow-blue-600/30 flex items-center gap-2 w-fit cursor-pointer">
            <i class="fas fa-plus"></i> Tambah Unit Servis
        </button>
    </div>

    <!-- POP-UP / MODAL FORM BOOKING -->
    <div x-show="openAddModal" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">
        
        <!-- Background Transparan -->
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="openAddModal = false"></div>

        <!-- Card Pop-up -->
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-4xl p-6 sm:p-8 space-y-6 z-10 max-h-[90vh] overflow-y-auto my-auto"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100">
            
            <!-- Tombol Close -->
            <button type="button" @click="openAddModal = false" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600 transition">
                <i class="fas fa-times text-xl"></i>
            </button>

            <!-- Header Pop-up -->
            <div class="text-center space-y-2">
                <span class="bg-blue-50 text-blue-600 border border-blue-200 text-[11px] font-bold px-4 py-1.5 rounded-full uppercase tracking-wider inline-block">
                    REGISTRASI SERVIS ONLINE
                </span>
                <h3 class="text-2xl font-black text-slate-900">Formulir Booking Servis Laptop</h3>
                <p class="text-xs text-slate-500">
                    Dapatkan Nomor Tiket Otomatis (SRV-YYYYMMDD-001) untuk memantau status perbaikan.
                </p>
            </div>

            <!-- Form Utama -->
            <form method="POST" action="{{ route('booking.store') }}" class="space-y-6 text-xs">
                @csrf

                <!-- Grid 2 Kolom -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- KOLOM KIRI: Data Diri Pelanggan -->
                    <div class="bg-slate-50/60 p-5 rounded-2xl border border-slate-200/80 space-y-4">
                        <h4 class="font-extrabold text-slate-900 text-sm flex items-center gap-2 pb-2 border-b border-slate-200">
                            <i class="fas fa-user-circle text-blue-600 text-base"></i> Data Diri Pelanggan
                        </h4>

                        <!-- Nama Lengkap -->
                        <div>
                            <label class="block font-bold text-slate-700 uppercase text-[10px] tracking-wider mb-1">Nama Lengkap *</label>
                            <input type="text" name="name" value="{{ auth()->user()->name }}" required
                                class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-600/30 focus:border-blue-600 transition">
                        </div>

                        <!-- No WhatsApp -->
                        <div>
                            <label class="block font-bold text-slate-700 uppercase text-[10px] tracking-wider mb-1">No. WhatsApp *</label>
                            <input type="text" name="whatsapp" placeholder="Contoh: 081234567890" required
                                class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-600/30 focus:border-blue-600 transition">
                        </div>

                        <!-- Alamat Lengkap -->
                        <div>
                            <label class="block font-bold text-slate-700 uppercase text-[10px] tracking-wider mb-1">Alamat Lengkap *</label>
                            <textarea name="address" rows="3" required placeholder="Alamat rumah / kantor..."
                                class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-600/30 focus:border-blue-600 transition resize-none"></textarea>
                        </div>
                    </div>

                    <!-- KOLOM KANAN: Detail Laptop & Keluhan -->
                    <div class="bg-slate-50/60 p-5 rounded-2xl border border-slate-200/80 space-y-4">
                        <h4 class="font-extrabold text-slate-900 text-sm flex items-center gap-2 pb-2 border-b border-slate-200">
                            <i class="fas fa-laptop text-blue-600 text-base"></i> Detail Laptop & Keluhan
                        </h4>

                        <!-- Merek & Tipe/Seri -->
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block font-bold text-slate-700 uppercase text-[10px] tracking-wider mb-1">Merek *</label>
                                <input type="text" name="laptop_brand" placeholder="Asus / Lenovo" required
                                    class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-3 focus:outline-none focus:ring-2 focus:ring-blue-600/30 focus:border-blue-600 transition">
                            </div>
                            <div>
                                <label class="block font-bold text-slate-700 uppercase text-[10px] tracking-wider mb-1">Tipe / Seri *</label>
                                <input type="text" name="laptop_type" placeholder="ROG G531" required
                                    class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-3 focus:outline-none focus:ring-2 focus:ring-blue-600/30 focus:border-blue-600 transition">
                            </div>
                        </div>

                        <!-- Kelengkapan Dititipkan -->
                        <div>
                            <label class="block font-bold text-slate-700 uppercase text-[10px] tracking-wider mb-1">Kelengkapan Dititipkan</label>
                            <input type="text" name="equipment" placeholder="Contoh: Charger, Tas Laptop"
                                class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-600/30 focus:border-blue-600 transition">
                        </div>

                        <!-- Keluhan Kerusakan -->
                        <div>
                            <label class="block font-bold text-slate-700 uppercase text-[10px] tracking-wider mb-1">Keluhan Kerusakan *</label>
                            <textarea name="complaint" rows="3" required placeholder="Jelaskan kerusakan laptop (misal: Mati total, layar bergaris, tidak bisa cas)..."
                                class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-600/30 focus:border-blue-600 transition resize-none"></textarea>
                        </div>
                    </div>

                </div>

                <!-- Tombol Aksi -->
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="openAddModal = false"
                        class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold px-6 py-3 rounded-2xl text-xs transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-extrabold px-8 py-3 rounded-2xl text-xs transition shadow-lg shadow-blue-600/30 flex items-center gap-2 cursor-pointer">
                        <i class="fas fa-paper-plane"></i> Kirim Booking Servis
                    </button>
                </div>
            </form>

        </div>
    </div> 
</div>

            @forelse($activeServices as $s)
                <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs space-y-6" x-data="serviceCard_{{ $s->id }}()">

                    <!-- Header -->
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

                    <!-- Complaint & Diagnosis -->
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

                    {{-- Approval Card for menunggu_persetujuan --}}
                    @if($s->status === 'menunggu_persetujuan')
                        <div class="bg-gradient-to-br from-purple-50 to-indigo-50 p-5 rounded-2xl border-2 border-purple-200 space-y-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-purple-600 text-white flex items-center justify-center shadow-lg shadow-purple-600/30">
                                    <i class="fas fa-file-signature text-lg"></i>
                                </div>
                                <div>
                                    <h4 class="font-extrabold text-slate-900 text-sm">Perlu Persetujuan Anda</h4>
                                    <p class="text-xs text-slate-500">Teknisi telah selesai melakukan diagnosa dan estimasi biaya telah tersedia.</p>
                                </div>
                            </div>

                            @if($s->latestApproval && $s->latestApproval->decision)
                                <div class="bg-white p-4 rounded-xl border {{ $s->latestApproval->decision === 'disetujui' ? 'border-emerald-200' : 'border-rose-200' }}">
                                    <div class="flex items-center gap-2">
                                        <i class="fas {{ $s->latestApproval->decision === 'disetujui' ? 'fa-circle-check text-emerald-600' : 'fa-circle-xmark text-rose-600' }}"></i>
                                        <span class="font-extrabold text-xs uppercase tracking-wide {{ $s->latestApproval->decision === 'disetujui' ? 'text-emerald-700' : 'text-rose-700' }}">
                                            {{ $s->latestApproval->decision === 'disetujui' ? 'Disetujui' : 'Ditolak' }}
                                        </span>
                                    </div>
                                    @if($s->latestApproval->note)
                                        <p class="text-xs text-slate-600 mt-2">{{ $s->latestApproval->note }}</p>
                                    @endif
                                    @if($s->latestApproval->approved_amount)
                                        <p class="text-xs font-bold text-blue-600 mt-1">Rp {{ number_format($s->latestApproval->approved_amount, 0, ',', '.') }}</p>
                                    @endif
                                </div>
                            @else
                                {{-- Cost Breakdown --}}
                                <div class="bg-white p-4 rounded-xl border border-slate-200 space-y-2 text-xs">
                                    <p class="font-bold text-slate-500 uppercase tracking-wider mb-2">Rincian Estimasi Biaya</p>
                                    <div class="flex justify-between items-center">
                                        <span class="text-slate-600">Jasa Perbaikan:</span>
                                        <span class="font-bold text-slate-800">Rp {{ number_format($s->service_fee, 0, ',', '.') }}</span>
                                    </div>
                                    @foreach($s->details as $detail)
                                        <div class="flex justify-between items-center">
                                            <span class="text-slate-600">{{ $detail->sparepart->part_name ?? 'Sparepart' }} ({{ $detail->quantity }}x)</span>
                                            <span class="font-bold text-slate-800">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                                        </div>
                                    @endforeach
                                    <div class="border-t border-slate-200 pt-2 flex justify-between items-center">
                                        <span class="font-extrabold text-slate-900">Total Estimasi:</span>
                                        <span class="font-black text-blue-600 text-sm">Rp {{ number_format($s->total_cost, 0, ',', '.') }}</span>
                                    </div>
                                </div>

                                <button @click="openApprovalModal = true"
                                    class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold py-3 px-6 rounded-2xl text-xs transition shadow-lg shadow-emerald-600/30 flex items-center justify-center gap-2">
                                    <i class="fas fa-check-circle"></i> Setujui Perbaikan
                                </button>

                                {{-- Approval Modal --}}
                                <div x-show="openApprovalModal" x-cloak
                                    class="fixed inset-0 z-50 flex items-center justify-center p-4"
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0"
                                    x-transition:enter-end="opacity-100"
                                    x-transition:leave="transition ease-in duration-150"
                                    x-transition:leave-start="opacity-100"
                                    x-transition:leave-end="opacity-0">
                                    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="openApprovalModal = false"></div>
                                    <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md p-8 space-y-6 z-10"
                                        x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0 scale-95"
                                        x-transition:enter-end="opacity-100 scale-100">
                                        <button @click="openApprovalModal = false" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 transition">
                                            <i class="fas fa-times text-lg"></i>
                                        </button>
                                        <div class="text-center space-y-2">
                                            <div class="w-14 h-14 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center mx-auto">
                                                <i class="fas fa-file-signature text-2xl"></i>
                                            </div>
                                            <h3 class="text-lg font-black text-slate-900">Setujui Perbaikan?</h3>
                                            <p class="text-xs text-slate-500">Anda setuju untuk melanjutkan perbaikan dengan estimasi biaya berikut.</p>
                                        </div>
                                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 space-y-2">
                                            <div class="flex justify-between text-xs">
                                                <span class="text-slate-500">Tiket:</span>
                                                <span class="font-bold text-slate-900">{{ $s->ticket_number }}</span>
                                            </div>
                                            <div class="flex justify-between text-xs">
                                                <span class="text-slate-500">Laptop:</span>
                                                <span class="font-bold text-slate-900">{{ $s->laptop_brand }} {{ $s->laptop_type }}</span>
                                            </div>
                                            <div class="flex justify-between text-xs">
                                                <span class="text-slate-500">Total Biaya:</span>
                                                <span class="font-black text-blue-600">Rp {{ number_format($s->total_cost, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                        <div class="flex gap-3">
                                            <button @click="openApprovalModal = false"
                                                class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-3 rounded-2xl text-xs transition">
                                                Batal
                                            </button>
                                            <a href="{{ route('customer.services.approve-form', $s->id) }}"
                                                class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold py-3 rounded-2xl text-xs text-center transition shadow-lg shadow-emerald-600/30 flex items-center justify-center gap-2">
                                                <i class="fas fa-check"></i> Ya, Setujui
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- Payment Card for selesai + Belum Bayar --}}
                    @if($s->status === 'selesai' && $s->transaction && $s->transaction->payment_status === 'Belum Bayar')
                        <div class="bg-gradient-to-br from-amber-50 to-orange-50 p-5 rounded-2xl border-2 border-amber-200 space-y-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center shadow-lg shadow-amber-500/30">
                                    <i class="fas fa-wallet text-lg"></i>
                                </div>
                                <div>
                                    <h4 class="font-extrabold text-slate-900 text-sm">Pembayaran Diperlukan</h4>
                                    <p class="text-xs text-slate-500">Perbaikan sudah selesai. Silakan lakukan pembayaran untuk mengambil unit.</p>
                                </div>
                            </div>

                            <div class="bg-white p-4 rounded-xl border border-slate-200 space-y-2 text-xs">
                                <div class="flex justify-between">
                                    <span class="text-slate-500">No. Invoice:</span>
                                    <span class="font-bold text-slate-900">{{ $s->transaction->invoice_number }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-500">Status:</span>
                                    <span class="bg-rose-100 text-rose-700 text-[11px] font-extrabold px-2.5 py-0.5 rounded-lg border border-rose-200">{{ $s->transaction->payment_status }}</span>
                                </div>
                                <div class="border-t border-slate-200 pt-2 flex justify-between">
                                    <span class="font-extrabold text-slate-900">Total yang harus dibayar:</span>
                                    <span class="font-black text-blue-600 text-sm">Rp {{ number_format($s->total_cost, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <button @click="openPaymentModal = true"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-extrabold py-3 px-6 rounded-2xl text-xs transition shadow-lg shadow-blue-600/30 flex items-center justify-center gap-2">
                                <i class="fas fa-credit-card"></i> Bayar Sekarang
                            </button>

                            {{-- Payment Modal --}}
                            <div x-show="openPaymentModal" x-cloak
                                class="fixed inset-0 z-50 flex items-center justify-center p-4"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0"
                                x-transition:enter-end="opacity-100"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100"
                                x-transition:leave-end="opacity-0">
                                <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="openPaymentModal = false"></div>
                                <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md p-8 space-y-6 z-10"
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 scale-95"
                                    x-transition:enter-end="opacity-100 scale-100">
                                    <button @click="openPaymentModal = false" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 transition">
                                        <i class="fas fa-times text-lg"></i>
                                    </button>
                                    <div class="text-center space-y-2">
                                        <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center mx-auto">
                                            <i class="fas fa-receipt text-2xl"></i>
                                        </div>
                                        <h3 class="text-lg font-black text-slate-900">Ringkasan Pembayaran</h3>
                                        <p class="text-xs text-slate-500">Pastikan data pembayaran Anda benar sebelum melanjutkan.</p>
                                    </div>
                                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 space-y-3">
                                        <div class="flex justify-between text-xs">
                                            <span class="text-slate-500">Invoice:</span>
                                            <span class="font-bold text-slate-900">{{ $s->transaction->invoice_number }}</span>
                                        </div>
                                        <div class="flex justify-between text-xs">
                                            <span class="text-slate-500">Tiket:</span>
                                            <span class="font-bold text-slate-900">{{ $s->ticket_number }}</span>
                                        </div>
                                        <div class="flex justify-between text-xs">
                                            <span class="text-slate-500">Laptop:</span>
                                            <span class="font-bold text-slate-900">{{ $s->laptop_brand }} {{ $s->laptop_type }}</span>
                                        </div>
                                        <div class="border-t border-slate-200 pt-3 flex justify-between">
                                            <span class="font-extrabold text-slate-900 text-sm">Total Pembayaran:</span>
                                            <span class="font-black text-blue-600">Rp {{ number_format($s->total_cost, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                    <div class="flex gap-3">
                                        <button @click="openPaymentModal = false"
                                            class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-3 rounded-2xl text-xs transition">
                                            Batal
                                        </button>
                                        <form method="POST" action="{{ route('customer.services.createPayment', $s->id) }}" class="flex-1">
                                            @csrf
                                            <button type="submit"
                                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-extrabold py-3 rounded-2xl text-xs transition shadow-lg shadow-blue-600/30 flex items-center justify-center gap-2">
                                                <i class="fas fa-check"></i> Konfirmasi Bayar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Discussion / Comments Section --}}
                    <div class="border-t border-slate-100 pt-5 space-y-4">
                        <h4 class="font-extrabold text-slate-900 text-sm flex items-center gap-2">
                            <i class="fas fa-comments text-blue-600"></i> Diskusi & Komentar
                        </h4>

                        <div class="space-y-3 max-h-80 overflow-y-auto px-1" id="comments-{{ $s->id }}">
                            @php
                                $visibleComments = $s->comments->where('is_internal', false)->sortBy('created_at');
                            @endphp
                            @forelse($visibleComments as $comment)
                                @php
                                    $isMine = $comment->user_id === auth()->id();
                                @endphp
                                <div class="flex {{ $isMine ? 'justify-end' : 'justify-start' }}">
                                    <div class="max-w-[75%] {{ $isMine ? 'bg-blue-600 text-white rounded-2xl rounded-br-md' : 'bg-slate-100 text-slate-800 rounded-2xl rounded-bl-md' }} p-4 space-y-1">
                                        @unless($isMine)
                                            <p class="text-[11px] font-extrabold text-blue-600">{{ $comment->user->name ?? 'Staf' }}</p>
                                        @endunless
                                        <p class="text-xs leading-relaxed">{{ $comment->message }}</p>
                                        <p class="text-[10px] {{ $isMine ? 'text-blue-200' : 'text-slate-400' }} text-right">{{ $comment->created_at->format('d M H:i') }}</p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-xs text-slate-400 text-center py-4">Belum ada komentar. Mulai percakapan dengan teknisi di bawah.</p>
                            @endforelse
                        </div>

                        <form method="POST" action="{{ route('comments.store', $s->id) }}" class="flex items-end gap-3">
                            @csrf
                            <div class="flex-1">
                                <textarea name="message" rows="2" required
                                    placeholder="Ketik pesan Anda..."
                                    class="w-full text-xs bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-600/30 focus:border-blue-600 transition resize-none placeholder:text-slate-400"></textarea>
                            </div>
                            <button type="submit"
                                class="w-10 h-10 bg-blue-600 hover:bg-blue-700 text-white rounded-xl flex items-center justify-center transition shadow-lg shadow-blue-600/30 flex-shrink-0">
                                <i class="fas fa-paper-plane text-xs"></i>
                            </button>
                        </form>
                    </div>

                </div>
            @empty
                <div class="bg-white p-12 rounded-3xl border border-slate-200/80 text-center text-slate-400">
                    Tidak ada perbaikan laptop aktif saat ini.
                </div>
            @endforelse
        </div>

        <!-- Completed Services Section -->
        @if($completedServices->isNotEmpty())
            <div class="space-y-6" x-data="{ openCompleted: false }">
                <button @click="openCompleted = !openCompleted"
                    class="flex items-center gap-3 group w-full text-left">
                    <h3 class="font-black text-slate-900 text-xl tracking-tight flex items-center gap-2">
                        <i class="fas fa-circle-check text-emerald-500"></i> Riwayat Selesai ({{ $completedServices->count() }})
                    </h3>
                    <div class="w-8 h-8 rounded-xl bg-slate-100 group-hover:bg-slate-200 flex items-center justify-center transition">
                        <i class="fas fa-chevron-down text-xs text-slate-500 transition-transform duration-300"
                            :class="{ 'rotate-180': openCompleted }"></i>
                    </div>
                </button>

                <div x-show="openCompleted" x-cloak
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-2"
                    class="space-y-4">

                    @foreach($completedServices as $s)
                        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs space-y-4">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                                <div>
                                    <span class="text-xs font-bold text-blue-600 uppercase tracking-widest bg-blue-50 px-3 py-1 rounded-lg border border-blue-200">
                                        Tiket: {{ $s->ticket_number }}
                                    </span>
                                    <h4 class="text-lg font-black text-slate-900 mt-2">{{ $s->laptop_brand }} {{ $s->laptop_type }}</h4>
                                    <p class="text-xs text-slate-400">Selesai: {{ $s->date_completed ? $s->date_completed->format('d M Y H:i') : '-' }}</p>
                                </div>
                                <div class="text-left sm:text-right space-y-1">
                                    <span class="{{ $s->status_badge_class }} px-4 py-1.5 rounded-2xl text-xs font-extrabold shadow-xs border inline-block">
                                        {{ $s->status_label }}
                                    </span>
                                    <p class="text-xs font-bold text-blue-600">Biaya: Rp {{ number_format($s->total_cost, 0, ',', '.') }}</p>
                                    @if($s->transaction)
                                        <span class="inline-block text-[11px] font-extrabold px-2.5 py-0.5 rounded-lg border {{ $s->transaction->payment_status === 'Lunas' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-rose-50 text-rose-700 border-rose-200' }}">
                                            {{ $s->transaction->payment_status }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            @if($s->status === 'selesai' && $s->transaction && $s->transaction->payment_status === 'Belum Bayar')
                                <div class="bg-gradient-to-br from-amber-50 to-orange-50 p-4 rounded-2xl border border-amber-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3"
                                    x-data="{ openPayCompleted_{{ $s->id }}: false }">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-amber-500 text-white flex items-center justify-center">
                                            <i class="fas fa-wallet"></i>
                                        </div>
                                        <div>
                                            <p class="font-bold text-xs text-slate-900">Menunggu Pembayaran</p>
                                            <p class="text-[11px] text-slate-500">Rp {{ number_format($s->total_cost, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                    <button @click="openPayCompleted_{{ $s->id }} = true"
                                        class="bg-blue-600 hover:bg-blue-700 text-white font-extrabold px-5 py-2.5 rounded-xl text-xs transition shadow-md flex items-center gap-2">
                                        <i class="fas fa-credit-card"></i> Bayar Sekarang
                                    </button>

                                    <div x-show="openPayCompleted_{{ $s->id }}" x-cloak
                                        class="fixed inset-0 z-50 flex items-center justify-center p-4"
                                        x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0"
                                        x-transition:enter-end="opacity-100"
                                        x-transition:leave="transition ease-in duration-150"
                                        x-transition:leave-start="opacity-100"
                                        x-transition:leave-end="opacity-0">
                                        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="openPayCompleted_{{ $s->id }} = false"></div>
                                        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md p-8 space-y-6 z-10">
                                            <button @click="openPayCompleted_{{ $s->id }} = false" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 transition">
                                                <i class="fas fa-times text-lg"></i>
                                            </button>
                                            <div class="text-center space-y-2">
                                                <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center mx-auto">
                                                    <i class="fas fa-receipt text-2xl"></i>
                                                </div>
                                                <h3 class="text-lg font-black text-slate-900">Ringkasan Pembayaran</h3>
                                            </div>
                                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 space-y-2">
                                                <div class="flex justify-between text-xs">
                                                    <span class="text-slate-500">Tiket:</span>
                                                    <span class="font-bold text-slate-900">{{ $s->ticket_number }}</span>
                                                </div>
                                                <div class="flex justify-between text-xs">
                                                    <span class="text-slate-500">Laptop:</span>
                                                    <span class="font-bold text-slate-900">{{ $s->laptop_brand }} {{ $s->laptop_type }}</span>
                                                </div>
                                                <div class="border-t border-slate-200 pt-2 flex justify-between">
                                                    <span class="font-extrabold text-slate-900">Total:</span>
                                                    <span class="font-black text-blue-600">Rp {{ number_format($s->total_cost, 0, ',', '.') }}</span>
                                                </div>
                                            </div>
                                            <div class="flex gap-3">
                                                <button @click="openPayCompleted_{{ $s->id }} = false"
                                                    class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-3 rounded-2xl text-xs transition">
                                                    Batal
                                                </button>
                                                <form method="POST" action="{{ route('customer.services.createPayment', $s->id) }}" class="flex-1">
                                                    @csrf
                                                    <button type="submit"
                                                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-extrabold py-3 rounded-2xl text-xs transition shadow-lg shadow-blue-600/30">
                                                        Konfirmasi Bayar
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    @push('scripts')
        <script>
            @foreach($activeServices as $s)
                function serviceCard_{{ $s->id }}() {
                    return {
                        openApprovalModal: false,
                        openPaymentModal: false
                    };
                }
            @endforeach
        </script>
    @endpush
</x-app-layout>
