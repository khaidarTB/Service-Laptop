<x-app-layout>
    <x-slot name="header">
        Tiket Servis: {{ $service->ticket_number }}
    </x-slot>

    <div class="space-y-8">
        <!-- Top Banner & Quick Actions -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div class="space-y-1">
                <div class="flex items-center gap-3 flex-wrap">
                    <h2 class="text-2xl font-black text-slate-900">{{ $service->laptop_brand }} {{ $service->laptop_type }}</h2>
                    <span class="{{ $service->status_badge_class }} px-3.5 py-1 rounded-xl text-xs font-extrabold shadow-2xs border">
                        {{ $service->status_label }}
                    </span>
                    @if($service->latestApproval)
                        @if($service->latestApproval->decision === 'approved')
                            <span class="bg-emerald-100 text-emerald-800 border border-emerald-300 px-3 py-1 rounded-xl text-xs font-extrabold shadow-2xs flex items-center gap-1">
                                <i class="fas fa-circle-check"></i> Disetujui Pelanggan
                            </span>
                        @else
                            <span class="bg-rose-100 text-rose-800 border border-rose-300 px-3 py-1 rounded-xl text-xs font-extrabold shadow-2xs flex items-center gap-1">
                                <i class="fas fa-circle-xmark"></i> Ditolak Pelanggan
                            </span>
                        @endif
                    @endif
                </div>
                <p class="text-xs text-slate-500 font-medium">
                    Customer: <b class="text-slate-800">{{ $service->customer ? $service->customer->name : '-' }}</b> ({{ $service->customer ? $service->customer->whatsapp : '-' }}) &bull; Diterima: {{ $service->date_received ? $service->date_received->format('d M Y H:i') : '-' }}
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <!-- WhatsApp Trigger Button -->
                @if($service->whatsapp_url)
                    <a href="{{ $service->whatsapp_url }}" target="_blank" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-4 py-2.5 rounded-2xl text-sm transition shadow-md shadow-emerald-600/20 flex items-center gap-2">
                        <i class="fab fa-whatsapp text-lg"></i> Kirim WA
                    </a>
                @endif

                <!-- Create Invoice / View Invoice -->
                @if($service->transaction)
                    <a href="{{ route('admin.transactions.invoice', $service->transaction->id) }}" target="_blank" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-4 py-2.5 rounded-2xl text-xs transition shadow-md shadow-indigo-600/20 flex items-center gap-1.5">
                        <i class="fas fa-print"></i> Cetak Invoice ({{ $service->transaction->payment_status }})
                    </a>
                @else
                    <div x-data="{ open: false }">
                        <button @click="open = true" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-4 py-2.5 rounded-2xl text-xs transition shadow-md shadow-blue-600/20 flex items-center gap-1.5">
                            <i class="fas fa-file-invoice-dollar"></i> Buat Invoice Tagihan
                        </button>
                        <div x-show="open" x-transition.opacity class="fixed inset-0 z-50">
                            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="open = false"></div>
                            <div class="fixed inset-0 flex items-center justify-center p-4">
                                <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-6 space-y-4" @click.stop>
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center">
                                            <i class="fas fa-file-invoice-dollar text-blue-600 text-xl"></i>
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-slate-900">Buat Invoice Tagihan</h3>
                                            <p class="text-xs text-slate-500">Anda akan diarahkan ke form pembuatan invoice.</p>
                                        </div>
                                    </div>
                                    <div class="bg-slate-50 p-4 rounded-2xl text-sm text-slate-600 space-y-1">
                                        <p>Total Biaya Servis: <span class="font-bold text-slate-900">Rp {{ number_format($service->total_cost, 0, ',', '.') }}</span></p>
                                        <p class="text-xs text-slate-400">Invoice akan dibuat untuk tiket {{ $service->ticket_number }}</p>
                                    </div>
                                    <div class="flex justify-end gap-3">
                                        <button @click="open = false" class="px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-100 transition">Batal</button>
                                        <a href="{{ route('admin.transactions.create', ['service_id' => $service->id]) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-5 py-2.5 rounded-xl text-sm transition">
                                            Lanjutkan
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <a href="{{ route('admin.services.edit', $service->id) }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold px-4 py-2.5 rounded-2xl text-xs transition">
                    <i class="fas fa-pen"></i> Edit
                </a>
            </div>
        </div>

        <!-- STATUS PROGRESS TIMELINE BAR -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs space-y-6">
            <h3 class="font-bold text-slate-900 text-sm uppercase tracking-wider flex items-center gap-2">
                <i class="fas fa-timeline text-blue-600"></i> Status Timeline Progress Perbaikan
            </h3>

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

            <div class="relative flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-l-2 md:border-l-0 md:border-t-2 border-slate-200 pl-4 md:pl-0 md:pt-6">
                @foreach($statusSteps as $key => $step)
                    @php
                        $stepIndex = array_search($key, $orderKeys);
                        $isPassed = $stepIndex <= $currentIndex && $service->status !== 'batal';
                        $isCurrent = $service->status === $key;
                    @endphp
                    <div class="relative flex md:flex-col items-center gap-3 md:gap-2 text-left md:text-center flex-1">
                        <div class="w-10 h-10 rounded-2xl flex items-center justify-center font-bold text-sm z-10 transition duration-300 {{ $isCurrent ? 'bg-blue-600 text-white ring-4 ring-blue-100 shadow-lg shadow-blue-600/30' : ($isPassed ? 'bg-emerald-500 text-white' : 'bg-slate-100 text-slate-400') }}">
                            <i class="fas {{ $step['icon'] }}"></i>
                        </div>
                        <div>
                            <p class="font-bold text-xs {{ $isCurrent ? 'text-blue-600 font-extrabold' : ($isPassed ? 'text-slate-900' : 'text-slate-400') }}">
                                {{ $step['label'] }}
                            </p>
                            @if($isCurrent)
                                <span class="text-[10px] text-blue-600 bg-blue-50 font-bold px-2 py-0.5 rounded-full border border-blue-200 inline-block mt-0.5">Status Saat Ini</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Status Change Modal Trigger -->
            <div class="pt-6 border-t border-slate-100 bg-slate-50/70 p-4 rounded-2xl" x-data="{ open: false }">
                <div class="flex items-center justify-between">
                    <div class="space-y-0.5">
                        <p class="text-sm font-bold text-slate-800">Ubah Status Perbaikan Tiket</p>
                        <p class="text-xs text-slate-500">Status saat ini: <span class="font-semibold">{{ $service->status_label }}</span></p>
                    </div>
                    <button @click="open = true" class="bg-slate-900 hover:bg-slate-800 text-white font-bold px-5 py-2.5 rounded-xl text-sm transition flex items-center gap-2">
                        <i class="fas fa-arrows-rotate"></i> Ubah Status
                    </button>
                </div>

                <div x-show="open" x-transition.opacity class="fixed inset-0 z-50">
                    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="open = false"></div>
                    <div class="fixed inset-0 flex items-center justify-center p-4">
                        <div class="bg-white rounded-3xl shadow-2xl max-w-lg w-full p-6 space-y-5" @click.stop>
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center">
                                    <i class="fas fa-arrows-rotate text-blue-600 text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-slate-900 text-lg">Ubah Status Tiket</h3>
                                    <p class="text-xs text-slate-500">Pilih status baru dan tambahkan catatan.</p>
                                </div>
                            </div>
                            <form action="{{ route('admin.services.updateStatus', $service->id) }}" method="POST" class="space-y-4">
                                @csrf
                                <div class="space-y-1.5">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Status Perbaikan</label>
                                    <select name="status" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                                        <option value="antrean" {{ $service->status === 'antrean' ? 'selected' : '' }}>Antrean (Masuk Servis)</option>
                                        <option value="pemeriksaan" {{ $service->status === 'pemeriksaan' ? 'selected' : '' }}>Pemeriksaan (Diagnosa)</option>
                                        <option value="menunggu_persetujuan" {{ $service->status === 'menunggu_persetujuan' ? 'selected' : '' }}>Menunggu Persetujuan Pelanggan</option>
                                        <option value="perbaikan" {{ $service->status === 'perbaikan' ? 'selected' : '' }}>Proses Perbaikan</option>
                                        <option value="selesai" {{ $service->status === 'selesai' ? 'selected' : '' }}>Selesai (Siap Diambil)</option>
                                        <option value="diambil" {{ $service->status === 'diambil' ? 'selected' : '' }}>Sudah Diambil Customer</option>
                                        <option value="batal" {{ $service->status === 'batal' ? 'selected' : '' }}>Batal Servis</option>
                                    </select>
                                </div>
                                <div class="space-y-1.5">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Catatan Perubahan <span class="text-slate-400 normal-case">(Opsional)</span></label>
                                    <input type="text" name="notes" placeholder="Contoh: Sparepart telah dipasang dan diuji..." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                                </div>
                                <div class="flex justify-end gap-3 pt-2">
                                    <button type="button" @click="open = false" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-100 transition">Batal</button>
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-2.5 rounded-xl text-sm transition shadow-md shadow-blue-600/20 flex items-center gap-2">
                                        <i class="fas fa-check"></i> Simpan Status
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Side Details -->
            <div class="lg:col-span-8 space-y-8">
                <!-- Complaint & Diagnosis Card -->
                <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs space-y-6">
                    <h3 class="font-bold text-slate-900 text-base border-b border-slate-100 pb-3 flex items-center gap-2">
                        <i class="fas fa-stethoscope text-blue-600"></i> Keluhan &amp; Hasil Diagnosa Teknisi
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-sm">
                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 space-y-1">
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Keluhan Pelanggan:</span>
                            <p class="font-semibold text-slate-800 leading-relaxed">{{ $service->complaint }}</p>
                        </div>
                        <div class="bg-blue-50/50 p-4 rounded-2xl border border-blue-100 space-y-1">
                            <span class="text-xs font-bold text-blue-600 uppercase tracking-wider">Diagnosa Teknisi:</span>
                            <p class="font-semibold text-slate-800 leading-relaxed">{{ $service->diagnosis ?? 'Belum ada diagnosa tercatat.' }}</p>
                        </div>
                    </div>
                </div>

                <!-- SPAREPART CART -->
                <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs space-y-6">
                    <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                        <h3 class="font-bold text-slate-900 text-base flex items-center gap-2">
                            <i class="fas fa-boxes-packing text-blue-600"></i> Sparepart Digunakan / Diganti
                        </h3>
                        <span class="text-xs font-black text-blue-700 bg-blue-50 px-3 py-1 rounded-xl border border-blue-200">
                            Total Part: Rp {{ number_format($service->details->sum('subtotal'), 0, ',', '.') }}
                        </span>
                    </div>

                    <!-- Add Sparepart Modal Trigger -->
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 flex items-center justify-between" x-data="{ open: false }">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                                <i class="fas fa-cart-plus text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-800">Tambah Sparepart</p>
                                <p class="text-xs text-slate-500">{{ $service->details->count() }} part terdaftar</p>
                            </div>
                        </div>
                        <button @click="open = true" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-5 py-2.5 rounded-xl text-sm transition shadow-md shadow-blue-600/20 flex items-center gap-1.5">
                            <i class="fas fa-plus"></i> Tambah Part
                        </button>

                        <div x-show="open" x-transition.opacity class="fixed inset-0 z-50">
                            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="open = false"></div>
                            <div class="fixed inset-0 flex items-center justify-center p-4">
                                <div class="bg-white rounded-3xl shadow-2xl max-w-lg w-full p-6 space-y-5" @click.stop>
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center">
                                            <i class="fas fa-cart-plus text-blue-600 text-xl"></i>
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-slate-900 text-lg">Tambah Sparepart</h3>
                                            <p class="text-xs text-slate-500">Pilih sparepart dari katalog dan tentukan jumlah.</p>
                                        </div>
                                    </div>
                                    <form action="{{ route('admin.services.addSparepart', $service->id) }}" method="POST" class="space-y-4">
                                        @csrf
                                        <div class="space-y-1.5">
                                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Sparepart Catalog</label>
                                            <select name="sparepart_id" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                                                <option value="">-- Pilih Sparepart --</option>
                                                @foreach($spareparts as $part)
                                                    <option value="{{ $part->id }}">{{ $part->part_name }} (Stok: {{ $part->stock }} - Rp {{ number_format($part->selling_price, 0, ',', '.') }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="space-y-1.5">
                                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Jumlah</label>
                                            <input type="number" name="quantity" value="1" min="1" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                                        </div>
                                        <div class="flex justify-end gap-3 pt-2">
                                            <button type="button" @click="open = false" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-100 transition">Batal</button>
                                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-2.5 rounded-xl text-sm transition shadow-md shadow-blue-600/20 flex items-center gap-2">
                                                <i class="fas fa-cart-plus"></i> Tambahkan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Spareparts Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-slate-700">
                            <thead class="bg-slate-50 text-xs font-bold uppercase tracking-wider text-slate-500">
                                <tr>
                                    <th class="py-3 px-4">Nama Sparepart</th>
                                    <th class="py-3 px-4 text-center">Harga Satuan</th>
                                    <th class="py-3 px-4 text-center">Jumlah</th>
                                    <th class="py-3 px-4 text-right">Subtotal</th>
                                    <th class="py-3 px-4 text-right">Hapus</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 font-medium">
                                @forelse($service->details as $detail)
                                    <tr>
                                        <td class="py-3.5 px-4 font-bold text-slate-900">{{ $detail->sparepart ? $detail->sparepart->part_name : 'Part' }}</td>
                                        <td class="py-3.5 px-4 text-center">Rp {{ number_format($detail->price_at_time, 0, ',', '.') }}</td>
                                        <td class="py-3.5 px-4 text-center font-bold">{{ $detail->quantity }}x</td>
                                        <td class="py-3.5 px-4 text-right font-black text-blue-600">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                        <td class="py-3.5 px-4 text-right" x-data="{ del: false }">
                                            <button @click="del = true" class="text-rose-500 hover:text-rose-700 text-xs font-bold"><i class="fas fa-trash"></i></button>
                                            <div x-show="del" x-transition.opacity class="fixed inset-0 z-50">
                                                <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="del = false"></div>
                                                <div class="fixed inset-0 flex items-center justify-center p-4">
                                                    <div class="bg-white rounded-3xl shadow-2xl max-w-sm w-full p-6 space-y-4" @click.stop>
                                                        <div class="flex items-center gap-3">
                                                            <div class="w-12 h-12 rounded-2xl bg-rose-50 flex items-center justify-center">
                                                                <i class="fas fa-trash text-rose-600 text-xl"></i>
                                                            </div>
                                                            <div>
                                                                <h3 class="font-bold text-slate-900">Hapus Sparepart?</h3>
                                                                <p class="text-xs text-slate-500">Part <b>{{ $detail->sparepart?->part_name }}</b> akan dihapus dari tiket ini.</p>
                                                            </div>
                                                        </div>
                                                        <div class="flex justify-end gap-3">
                                                            <button @click="del = false" class="px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-100 transition">Batal</button>
                                                            <form action="{{ route('admin.services.removeSparepart', [$service->id, $detail->id]) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white font-bold px-5 py-2.5 rounded-xl text-sm transition shadow-md shadow-rose-600/20">
                                                                    <i class="fas fa-trash mr-1"></i> Hapus
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-6 text-center text-slate-400 text-xs">Belum ada sparepart yang ditambahkan ke tiket ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- PHOTO DOCUMENTATION GALLERY -->
                <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs space-y-6">
                    <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                        <h3 class="font-bold text-slate-900 text-base flex items-center gap-2">
                            <i class="fas fa-camera text-blue-600"></i> Dokumentasi Kerusakan &amp; Hasil Perbaikan
                        </h3>
                        <span class="text-xs font-black text-blue-700 bg-blue-50 px-3 py-1 rounded-xl border border-blue-200">
                            {{ $service->photos->count() }} Foto
                        </span>
                    </div>

                    <!-- Upload Photo Modal Trigger -->
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 flex items-center justify-between" x-data="{ open: false }">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                                <i class="fas fa-cloud-arrow-up text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-800">Upload Foto Dokumentasi</p>
                                <p class="text-xs text-slate-500">Sebelum atau sesudah perbaikan</p>
                            </div>
                        </div>
                        <button @click="open = true" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-5 py-2.5 rounded-xl text-sm transition shadow-md shadow-blue-600/20 flex items-center gap-1.5">
                            <i class="fas fa-cloud-arrow-up"></i> Upload Foto
                        </button>

                        <div x-show="open" x-transition.opacity class="fixed inset-0 z-50">
                            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="open = false"></div>
                            <div class="fixed inset-0 flex items-center justify-center p-4">
                                <div class="bg-white rounded-3xl shadow-2xl max-w-lg w-full p-6 space-y-5" @click.stop>
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center">
                                            <i class="fas fa-cloud-arrow-up text-blue-600 text-xl"></i>
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-slate-900 text-lg">Upload Foto Dokumentasi</h3>
                                            <p class="text-xs text-slate-500">Upload file atau masukkan URL gambar.</p>
                                        </div>
                                    </div>
                                    <form action="{{ route('admin.services.uploadPhoto', $service->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                        @csrf
                                        <div class="space-y-1.5">
                                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Tipe Foto</label>
                                            <select name="photo_type" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                                                <option value="before">Sebelum Perbaikan (Kerusakan)</option>
                                                <option value="after">Setelah Perbaikan (Hasil)</option>
                                            </select>
                                        </div>
                                        <div class="space-y-1.5">
                                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Berkas Foto</label>
                                            <input type="file" name="image" accept="image/*" class="w-full text-sm text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 file:cursor-pointer">
                                        </div>
                                        <div class="space-y-1.5">
                                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Atau URL Gambar</label>
                                            <input type="url" name="image_url" placeholder="https://..." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                                        </div>
                                        <div class="space-y-1.5">
                                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Catatan / Keterangan</label>
                                            <input type="text" name="description" placeholder="Contoh: Chipset terbakar..." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                                        </div>
                                        <div class="flex justify-end gap-3 pt-2">
                                            <button type="button" @click="open = false" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-100 transition">Batal</button>
                                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-2.5 rounded-xl text-sm transition shadow-md shadow-blue-600/20 flex items-center gap-2">
                                                <i class="fas fa-cloud-arrow-up"></i> Upload
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Photos Grid -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        @forelse($service->photos as $photo)
                            <div class="relative group rounded-2xl overflow-hidden border border-slate-200 bg-slate-100 aspect-square" x-data="{ del: false }">
                                <img src="{{ $photo->image }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                <div class="absolute inset-0 bg-slate-900/60 opacity-0 group-hover:opacity-100 transition p-3 flex flex-col justify-between text-white text-xs">
                                    <span class="font-bold capitalize bg-white/20 backdrop-blur-sm px-2 py-0.5 rounded-md w-fit">
                                        {{ $photo->photo_type === 'before' ? 'Sebelum' : 'Sesudah' }}
                                    </span>
                                    <div>
                                        <p class="text-[11px] line-clamp-2">{{ $photo->description }}</p>
                                        <button @click="del = true" class="mt-2 text-rose-400 hover:text-rose-200 font-bold text-[10px]"><i class="fas fa-trash"></i> Hapus Foto</button>
                                    </div>
                                </div>
                                <div x-show="del" x-transition.opacity class="fixed inset-0 z-50">
                                    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="del = false"></div>
                                    <div class="fixed inset-0 flex items-center justify-center p-4">
                                        <div class="bg-white rounded-3xl shadow-2xl max-w-sm w-full p-6 space-y-4" @click.stop>
                                            <div class="flex items-center gap-3">
                                                <div class="w-12 h-12 rounded-2xl bg-rose-50 flex items-center justify-center">
                                                    <i class="fas fa-image-slash text-rose-600 text-xl"></i>
                                                </div>
                                                <div>
                                                    <h3 class="font-bold text-slate-900">Hapus Foto?</h3>
                                                    <p class="text-xs text-slate-500">Foto dokumentasi akan dihapus permanen.</p>
                                                </div>
                                            </div>
                                            <div class="flex justify-end gap-3">
                                                <button @click="del = false" class="px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-100 transition">Batal</button>
                                                <form action="{{ route('admin.services.deletePhoto', [$service->id, $photo->id]) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white font-bold px-5 py-2.5 rounded-xl text-sm transition shadow-md shadow-rose-600/20">
                                                        <i class="fas fa-trash mr-1"></i> Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full py-6 text-center text-slate-400 text-xs">Belum ada foto dokumentasi.</div>
                        @endforelse
                    </div>
                </div>

                <!-- DISKUSI / KOMENTAR TIKET -->
                <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs space-y-6" x-data="{ internal: false }">
                    <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                        <h3 class="font-bold text-slate-900 text-base flex items-center gap-2">
                            <i class="fas fa-comments text-blue-600"></i> Diskusi / Komentar Tiket
                        </h3>
                        @php
                            $publicComments = $service->comments->filter(fn($c) => !$c->is_internal);
                        @endphp
                        <span class="text-xs font-black text-blue-700 bg-blue-50 px-3 py-1 rounded-xl border border-blue-200">
                            {{ $service->comments->count() }} Komentar
                        </span>
                    </div>

                    <!-- Chat Bubble List -->
                    <div class="space-y-4 max-h-[500px] overflow-y-auto pr-1">
                        @forelse($service->comments as $comment)
                            @php
                                $isOwn = auth()->id() === $comment->user_id;
                                $isAdmin = $comment->user?->isAdmin();
                                $isTeknisi = $comment->user?->isTechnician();
                            @endphp
                            <div class="flex gap-3 {{ $isAdmin || $isTeknisi ? 'flex-row-reverse' : '' }}" x-data="{ del: false }">
                                <!-- Avatar -->
                                <div class="w-9 h-9 rounded-xl flex-shrink-0 flex items-center justify-center text-xs font-extrabold
                                    {{ $isAdmin ? 'bg-blue-600 text-white' : ($isTeknisi ? 'bg-cyan-600 text-white' : 'bg-slate-200 text-slate-700') }}">
                                    {{ strtoupper(substr($comment->user?->name ?? '?', 0, 1)) }}
                                </div>
                                <!-- Bubble -->
                                <div class="max-w-[75%] space-y-1">
                                    <div class="flex items-center gap-2 {{ $isAdmin || $isTeknisi ? 'flex-row-reverse' : '' }}">
                                        <span class="text-xs font-bold text-slate-900">{{ $comment->user?->name ?? 'Unknown' }}</span>
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full
                                            {{ $isAdmin ? 'bg-blue-100 text-blue-700' : ($isTeknisi ? 'bg-cyan-100 text-cyan-700' : 'bg-slate-100 text-slate-600') }}">
                                            {{ $isAdmin ? 'Admin' : ($isTeknisi ? 'Teknisi' : 'Customer') }}
                                        </span>
                                        @if($comment->is_internal)
                                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-amber-100 text-amber-700">
                                                <i class="fas fa-lock mr-0.5"></i> Internal
                                            </span>
                                        @endif
                                    </div>
                                    <div class="p-3.5 rounded-2xl text-sm leading-relaxed {{ ($isAdmin || $isTeknisi) ? 'bg-blue-600 text-white rounded-tr-md' : 'bg-slate-100 text-slate-800 rounded-tl-md' }}">
                                        {{ $comment->message }}
                                    </div>
                                    <div class="flex items-center gap-2 {{ $isAdmin || $isTeknisi ? 'justify-end' : '' }}">
                                        <span class="text-[10px] text-slate-400">{{ $comment->created_at ? $comment->created_at->diffForHumans() : '' }}</span>
                                        @if($isOwn)
                                            <button @click="del = true" class="text-[10px] text-rose-400 hover:text-rose-600 font-bold"><i class="fas fa-trash"></i></button>
                                        @endif
                                    </div>

                                    <!-- Delete Comment Modal -->
                                    <div x-show="del" x-transition.opacity class="fixed inset-0 z-50">
                                        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="del = false"></div>
                                        <div class="fixed inset-0 flex items-center justify-center p-4">
                                            <div class="bg-white rounded-3xl shadow-2xl max-w-sm w-full p-6 space-y-4" @click.stop>
                                                <div class="flex items-center gap-3">
                                                    <div class="w-12 h-12 rounded-2xl bg-rose-50 flex items-center justify-center">
                                                        <i class="fas fa-trash text-rose-600 text-xl"></i>
                                                    </div>
                                                    <div>
                                                        <h3 class="font-bold text-slate-900">Hapus Komentar?</h3>
                                                        <p class="text-xs text-slate-500">Komentar akan dihapus permanen.</p>
                                                    </div>
                                                </div>
                                                <div class="flex justify-end gap-3">
                                                    <button @click="del = false" class="px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-100 transition">Batal</button>
                                                    <form action="{{ route('comments.destroy', [$service->id, $comment->id]) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white font-bold px-5 py-2.5 rounded-xl text-sm transition shadow-md shadow-rose-600/20">
                                                            <i class="fas fa-trash mr-1"></i> Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="py-8 text-center">
                                <div class="w-14 h-14 rounded-2xl bg-slate-100 flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-comments text-slate-300 text-xl"></i>
                                </div>
                                <p class="text-sm text-slate-400 font-medium">Belum ada komentar.</p>
                                <p class="text-xs text-slate-300">Mulai diskusi tentang tiket ini.</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Add Comment Form -->
                    <form action="{{ route('comments.store', $service->id) }}" method="POST" class="pt-4 border-t border-slate-100 space-y-3">
                        @csrf
                        <div class="space-y-1.5">
                            <textarea name="message" rows="3" placeholder="Tulis komentar atau catatan..." required class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none resize-none transition"></textarea>
                        </div>
                        <div class="flex items-center justify-between">
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="checkbox" name="is_internal" value="1" x-model="internal" class="w-4 h-4 rounded-lg border-slate-300 text-blue-600 focus:ring-blue-500">
                                <span class="text-xs font-semibold text-slate-600 group-hover:text-slate-900 transition">
                                    <i class="fas fa-lock mr-1 text-amber-500"></i> Internal Note
                                    <span class="text-slate-400">(hanya terlihat oleh staf)</span>
                                </span>
                            </label>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-2.5 rounded-xl text-sm transition shadow-md shadow-blue-600/20 flex items-center gap-2">
                                <i class="fas fa-paper-plane"></i> Kirim
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Side Column -->
            <div class="lg:col-span-4 space-y-8">
                <!-- Approval Status Card -->
                @if($service->latestApproval)
                    <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs space-y-4">
                        <h3 class="font-bold text-slate-900 text-base border-b border-slate-100 pb-3 flex items-center gap-2">
                            <i class="fas fa-file-signature text-blue-600"></i> Keputusan Pelanggan
                        </h3>
                        <div class="space-y-3">
                            <div class="flex items-center gap-3">
                                @if($service->latestApproval->decision === 'approved')
                                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center">
                                        <i class="fas fa-circle-check text-emerald-600 text-xl"></i>
                                    </div>
                                    <div>
                                        <p class="font-bold text-emerald-700">Disetujui</p>
                                        <p class="text-xs text-slate-500">Pelanggan menyetujui estimasi biaya</p>
                                    </div>
                                @else
                                    <div class="w-12 h-12 rounded-2xl bg-rose-50 flex items-center justify-center">
                                        <i class="fas fa-circle-xmark text-rose-600 text-xl"></i>
                                    </div>
                                    <div>
                                        <p class="font-bold text-rose-700">Ditolak</p>
                                        <p class="text-xs text-slate-500">Pelanggan menolak estimasi biaya</p>
                                    </div>
                                @endif
                            </div>
                            @if($service->latestApproval->approved_amount)
                                <div class="bg-slate-50 p-3 rounded-xl">
                                    <p class="text-xs text-slate-500">Disetujui: <span class="font-bold text-slate-900">Rp {{ number_format($service->latestApproval->approved_amount, 0, ',', '.') }}</span></p>
                                </div>
                            @endif
                            @if($service->latestApproval->note)
                                <div class="bg-slate-50 p-3 rounded-xl">
                                    <p class="text-xs text-slate-500">Catatan Pelanggan:</p>
                                    <p class="text-sm text-slate-800 font-medium mt-1">{{ $service->latestApproval->note }}</p>
                                </div>
                            @endif
                            <p class="text-[10px] text-slate-400">
                                {{ $service->latestApproval->created_at ? $service->latestApproval->created_at->format('d M Y H:i') : '' }}
                            </p>
                        </div>
                    </div>
                @endif

                <!-- Financial Summary Card -->
                <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs space-y-4">
                    <h3 class="font-bold text-slate-900 text-base border-b border-slate-100 pb-3 flex items-center gap-2">
                        <i class="fas fa-calculator text-blue-600"></i> Rincian Biaya
                    </h3>

                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between text-slate-600">
                            <span>Jasa Perbaikan:</span>
                            <span class="font-bold text-slate-900">Rp {{ number_format($service->service_fee, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>Total Sparepart:</span>
                            <span class="font-bold text-slate-900">Rp {{ number_format($service->details->sum('subtotal'), 0, ',', '.') }}</span>
                        </div>
                        <div class="pt-3 border-t border-slate-100 flex justify-between items-center">
                            <span class="font-extrabold text-slate-900">Total Tagihan:</span>
                            <span class="text-xl font-black text-blue-600">Rp {{ number_format($service->total_cost, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- History Audit Logs -->
                <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs space-y-4">
                    <h3 class="font-bold text-slate-900 text-base border-b border-slate-100 pb-3 flex items-center gap-2">
                        <i class="fas fa-history text-blue-600"></i> Riwayat Perubahan Status
                    </h3>

                    <div class="divide-y divide-slate-100 max-h-80 overflow-y-auto">
                        @forelse($service->statusLogs as $log)
                            <div class="py-3 space-y-1">
                                <div class="flex justify-between items-center text-xs">
                                    <span class="font-bold text-slate-900 capitalize">{{ str_replace('_', ' ', $log->new_status) }}</span>
                                    <span class="text-[10px] text-slate-400">{{ $log->created_at ? $log->created_at->format('d M H:i') : '' }}</span>
                                </div>
                                <p class="text-xs text-slate-500">{{ $log->notes }}</p>
                                <p class="text-[10px] text-slate-400 italic">Oleh: {{ $log->user ? $log->user->name : 'Sistem' }}</p>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 text-center py-4">Belum ada riwayat.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
