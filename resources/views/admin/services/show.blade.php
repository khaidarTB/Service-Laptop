<x-app-layout>
    <x-slot name="header">
        Tiket Servis: {{ $service->ticket_number }}
    </x-slot>

    <div class="space-y-8">
        <!-- Top Banner & Quick Actions -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div class="space-y-1">
                <div class="flex items-center gap-3">
                    <h2 class="text-2xl font-black text-slate-900">{{ $service->laptop_brand }} {{ $service->laptop_type }}</h2>
                    <span class="{{ $service->status_badge_class }} px-3.5 py-1 rounded-xl text-xs font-extrabold shadow-2xs border">
                        {{ $service->status_label }}
                    </span>
                </div>
                <p class="text-xs text-slate-500 font-medium">
                    Customer: <b class="text-slate-800">{{ $service->customer ? $service->customer->name : '-' }}</b> ({{ $service->customer ? $service->customer->whatsapp : '-' }}) • Diterima: {{ $service->date_received ? $service->date_received->format('d M Y H:i') : '-' }}
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <!-- WhatsApp Trigger Button -->
                @if($service->whatsapp_url)
                    <a href="{{ $service->whatsapp_url }}" target="_blank" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-4 py-2.5 rounded-2xl text-xs transition shadow-md shadow-emerald-600/20 flex items-center gap-1.5">
                        <i class="fab fa-whatsapp text-lg"></i> Kirim Notifikasi WA
                    </a>
                @endif

                <!-- Create Invoice / View Invoice -->
                @if($service->transaction)
                    <a href="{{ route('admin.transactions.invoice', $service->transaction->id) }}" target="_blank" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-4 py-2.5 rounded-2xl text-xs transition shadow-md shadow-indigo-600/20 flex items-center gap-1.5">
                        <i class="fas fa-print"></i> Cetak Invoice ({{ $service->transaction->payment_status }})
                    </a>
                @else
                    <a href="{{ route('admin.transactions.create', ['service_id' => $service->id]) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-4 py-2.5 rounded-2xl text-xs transition shadow-md shadow-blue-600/20 flex items-center gap-1.5">
                        <i class="fas fa-file-invoice-dollar"></i> Buat Invoice Tagihan
                    </a>
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

            <!-- Quick Status Change Form -->
            <div class="pt-6 border-t border-slate-100 bg-slate-50/70 p-4 rounded-2xl">
                <form action="{{ route('admin.services.updateStatus', $service->id) }}" method="POST" class="flex flex-col sm:flex-row items-end gap-3">
                    @csrf
                    <div class="flex-1 space-y-1 w-full">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Ubah Status Perbaikan Tiket Ini</label>
                        <select name="status" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold bg-white">
                            <option value="antrean" {{ $service->status === 'antrean' ? 'selected' : '' }}>Antrean (Masuk Servis)</option>
                            <option value="pemeriksaan" {{ $service->status === 'pemeriksaan' ? 'selected' : '' }}>Pemeriksaan (Diagnosa)</option>
                            <option value="menunggu_persetujuan" {{ $service->status === 'menunggu_persetujuan' ? 'selected' : '' }}>Menunggu Persetujuan Pelanggan</option>
                            <option value="perbaikan" {{ $service->status === 'perbaikan' ? 'selected' : '' }}>Proses Perbaikan</option>
                            <option value="selesai" {{ $service->status === 'selesai' ? 'selected' : '' }}>Selesai (Siap Diambil)</option>
                            <option value="diambil" {{ $service->status === 'diambil' ? 'selected' : '' }}>Sudah Diambil Customer</option>
                            <option value="batal" {{ $service->status === 'batal' ? 'selected' : '' }}>Batal Servis</option>
                        </select>
                    </div>
                    <div class="flex-1 space-y-1 w-full">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Catatan Perubahan (Opsional)</label>
                        <input type="text" name="notes" placeholder="Contoh: Sparepart telah dipasang dan diuji..." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm bg-white">
                    </div>
                    <button type="submit" class="w-full sm:w-auto bg-slate-900 hover:bg-slate-800 text-white font-bold px-5 py-2.5 rounded-xl text-sm transition">
                        Update Status
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Side Details: Diagnosis, Complaint, Sparepart Cart Assign -->
            <div class="lg:col-span-8 space-y-8">
                <!-- Complaint & Diagnosis Card -->
                <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs space-y-6">
                    <h3 class="font-bold text-slate-900 text-base border-b border-slate-100 pb-3 flex items-center gap-2">
                        <i class="fas fa-stethoscope text-blue-600"></i> Keluhan & Hasil Diagnosa Teknisi
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

                <!-- SPAREPART CART SELECTION & LIST -->
                <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs space-y-6">
                    <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                        <h3 class="font-bold text-slate-900 text-base flex items-center gap-2">
                            <i class="fas fa-boxes-packing text-blue-600"></i> Sparepart Digunakan / Diganti
                        </h3>
                        <span class="text-xs font-black text-blue-700 bg-blue-50 px-3 py-1 rounded-xl border border-blue-200">
                            Total Part: Rp {{ number_format($service->details->sum('subtotal'), 0, ',', '.') }}
                        </span>
                    </div>

                    <!-- Add Sparepart Form -->
                    <form action="{{ route('admin.services.addSparepart', $service->id) }}" method="POST" class="flex flex-col sm:flex-row gap-3 bg-slate-50 p-4 rounded-2xl border border-slate-200">
                        @csrf
                        <div class="flex-1">
                            <select name="sparepart_id" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium bg-white">
                                <option value="">-- Pilih Sparepart Catalog --</option>
                                @foreach($spareparts as $part)
                                    <option value="{{ $part->id }}">{{ $part->part_name }} (Stok: {{ $part->stock }} - Rp {{ number_format($part->selling_price, 0, ',', '.') }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-full sm:w-28">
                            <input type="number" name="quantity" value="1" min="1" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium bg-white">
                        </div>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-5 py-2.5 rounded-xl text-sm transition flex items-center justify-center gap-1.5 whitespace-nowrap">
                            <i class="fas fa-cart-plus"></i> Tambah Part
                        </button>
                    </form>

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
                                        <td class="py-3.5 px-4 text-right">
                                            <form action="{{ route('admin.services.removeSparepart', [$service->id, $detail->id]) }}" method="POST" class="inline" onsubmit="return confirm('Hapus part dari tiket?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-rose-500 hover:text-rose-700 text-xs font-bold"><i class="fas fa-trash"></i></button>
                                            </form>
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
                            <i class="fas fa-camera text-blue-600"></i> Dokumentasi Kerusakan & Hasil Perbaikan
                        </h3>
                    </div>

                    <!-- Upload Photo Form -->
                    <form action="{{ route('admin.services.uploadPhoto', $service->id) }}" method="POST" enctype="multipart/form-data" class="bg-slate-50 p-4 rounded-2xl border border-slate-200 space-y-3">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Tipe Foto</label>
                                <select name="photo_type" required class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs bg-white">
                                    <option value="before">Sebelum Perbaikan (Kerusakan)</option>
                                    <option value="after">Setelah Perbaikan (Hasil)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Pilih Berkas Foto</label>
                                <input type="file" name="image" accept="image/*" class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Catatan / Keterangan</label>
                                <input type="text" name="description" placeholder="Contoh: Chipset terbakar..." class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs bg-white">
                            </div>
                        </div>
                        <div class="flex justify-between items-center pt-2">
                            <input type="url" name="image_url" placeholder="Atau masukkan URL foto https://..." class="w-full max-w-md px-3 py-1.5 rounded-lg border border-slate-200 text-xs">
                            <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white font-bold px-4 py-2 rounded-xl text-xs transition">
                                Upload Foto
                            </button>
                        </div>
                    </form>

                    <!-- Photos Grid -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        @forelse($service->photos as $photo)
                            <div class="relative group rounded-2xl overflow-hidden border border-slate-200 bg-slate-100 aspect-square">
                                <img src="{{ $photo->image }}" class="w-full h-full object-cover group-hover:scale-105 transition">
                                <div class="absolute inset-0 bg-slate-900/60 opacity-0 group-hover:opacity-100 transition p-3 flex flex-col justify-between text-white text-xs">
                                    <span class="font-bold capitalize bg-white/20 px-2 py-0.5 rounded-md w-fit">
                                        {{ $photo->photo_type === 'before' ? 'Sebelum' : 'Sesudah' }}
                                    </span>
                                    <div>
                                        <p class="text-[11px] line-clamp-2">{{ $photo->description }}</p>
                                        <form action="{{ route('admin.services.deletePhoto', [$service->id, $photo->id]) }}" method="POST" class="mt-2" onsubmit="return confirm('Hapus foto ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-rose-400 hover:text-rose-200 font-bold text-[10px]"><i class="fas fa-trash"></i> Hapus Foto</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full py-6 text-center text-slate-400 text-xs">Belum ada foto dokumentasi.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Right Side Column: Costs, Technician, Audit Logs -->
            <div class="lg:col-span-4 space-y-8">
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
