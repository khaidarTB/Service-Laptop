<x-app-layout>
    <x-slot name="header">Workbench Perbaikan: {{ $task->ticket_number }}</x-slot>

    <div class="space-y-8">
        <!-- Top Info Header -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div class="space-y-1">
                <div class="flex items-center gap-3">
                    <h2 class="text-2xl font-black text-slate-900">{{ $task->laptop_brand }} {{ $task->laptop_type }}</h2>
                    <span class="{{ $task->status_badge_class }} px-3.5 py-1 rounded-xl text-xs font-extrabold border">
                        {{ $task->status_label }}
                    </span>
                </div>
                <p class="text-xs text-slate-500 font-medium">
                    Customer: <b>{{ $task->customer ? $task->customer->name : '-' }}</b> • SN: {{ $task->serial_number ?? 'N/A' }} • Kelengkapan: {{ $task->equipment ?? 'Tidak Ada' }}
                </p>
            </div>

            @if($task->whatsapp_url)
                <a href="{{ $task->whatsapp_url }}" target="_blank" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-4 py-2.5 rounded-2xl text-xs transition shadow-md shadow-emerald-600/20 flex items-center gap-1.5">
                    <i class="fab fa-whatsapp text-lg"></i> Notifikasi WA Customer
                </a>
            @endif
        </div>

        <!-- STATUS UPDATER FORM -->
        <div class="bg-slate-900 text-white p-6 sm:p-8 rounded-3xl shadow-xl space-y-4">
            <h3 class="font-extrabold text-lg flex items-center gap-2">
                <i class="fas fa-sliders text-blue-400"></i> Update Status Perbaikan Unit
            </h3>

            <form action="{{ route('teknisi.tasks.updateStatus', $task->id) }}" method="POST" class="flex flex-col sm:flex-row items-end gap-4">
                @csrf
                <div class="flex-1 space-y-1 w-full">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Pilih Status Terbaru</label>
                    <select name="status" class="w-full px-4 py-3 rounded-2xl border border-slate-700 text-sm font-semibold bg-slate-800 text-white">
                        <option value="antrean" {{ $task->status === 'antrean' ? 'selected' : '' }}>Antrean (Masuk Servis)</option>
                        <option value="pemeriksaan" {{ $task->status === 'pemeriksaan' ? 'selected' : '' }}>Pemeriksaan (Diagnosa Kerusakan)</option>
                        <option value="menunggu_persetujuan" {{ $task->status === 'menunggu_persetujuan' ? 'selected' : '' }}>Menunggu Persetujuan Customer</option>
                        <option value="perbaikan" {{ $task->status === 'perbaikan' ? 'selected' : '' }}>Dalam Perbaikan</option>
                        <option value="selesai" {{ $task->status === 'selesai' ? 'selected' : '' }}>Servis Selesai (Siap Diambil)</option>
                    </select>
                </div>
                <div class="flex-1 space-y-1 w-full">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Catatan Pengerjaan Teknisi</label>
                    <input type="text" name="notes" placeholder="Tuliskan perkembangan perbaikan..." class="w-full px-4 py-3 rounded-2xl border border-slate-700 text-sm bg-slate-800 text-white">
                </div>
                <button type="submit" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-extrabold px-6 py-3 rounded-2xl text-sm transition shadow-lg shadow-blue-600/30">
                    Simpan Status
                </button>
            </form>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Side: Diagnosis Form & Spareparts -->
            <div class="lg:col-span-8 space-y-8">
                <!-- Diagnosis & Estimate Editor -->
                <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs space-y-6">
                    <h3 class="font-bold text-slate-900 text-base border-b border-slate-100 pb-3 flex items-center gap-2">
                        <i class="fas fa-stethoscope text-blue-600"></i> Form Diagnosa & Jasa Teknisi
                    </h3>

                    <form action="{{ route('teknisi.tasks.update', $task->id) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Hasil Diagnosa Kerusakan *</label>
                            <textarea name="diagnosis" required rows="4" placeholder="Jelaskan kerusakan teknis yang ditemukan setelah pemeriksaan..." class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm font-medium">{{ old('diagnosis', $task->diagnosis) }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Biaya Jasa Servis (Rp)</label>
                                <input type="number" name="service_fee" value="{{ old('service_fee', $task->service_fee) }}" class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm font-medium">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Estimasi Biaya Total (Rp)</label>
                                <input type="number" name="estimated_cost" value="{{ old('estimated_cost', $task->estimated_cost) }}" class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm font-medium">
                            </div>
                        </div>

                        <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white font-bold px-6 py-3 rounded-2xl text-sm transition">
                            Simpan Diagnosa & Biaya
                        </button>
                    </form>
                </div>

                <!-- Sparepart Selection Cart -->
                <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs space-y-6">
                    <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                        <h3 class="font-bold text-slate-900 text-base flex items-center gap-2">
                            <i class="fas fa-box-open text-blue-600"></i> Pasang Sparepart / Komponen
                        </h3>
                        <span class="text-xs font-black text-blue-700 bg-blue-50 px-3 py-1 rounded-xl border border-blue-200">
                            Total Part: Rp {{ number_format($task->details->sum('subtotal'), 0, ',', '.') }}
                        </span>
                    </div>

                    <form action="{{ route('teknisi.tasks.addSparepart', $task->id) }}" method="POST" class="flex flex-col sm:flex-row gap-3 bg-slate-50 p-4 rounded-2xl border border-slate-200">
                        @csrf
                        <div class="flex-1">
                            <select name="sparepart_id" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium bg-white">
                                <option value="">-- Pilih Part dari Stok Inventory --</option>
                                @foreach($spareparts as $part)
                                    <option value="{{ $part->id }}">{{ $part->part_name }} (Stok: {{ $part->stock }} - Rp {{ number_format($part->selling_price, 0, ',', '.') }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-full sm:w-28">
                            <input type="number" name="quantity" value="1" min="1" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium bg-white">
                        </div>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-5 py-2.5 rounded-xl text-sm transition flex items-center justify-center gap-1.5 whitespace-nowrap">
                            <i class="fas fa-plus"></i> Pasang Part
                        </button>
                    </form>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-slate-700">
                            <thead class="bg-slate-50 text-xs font-bold uppercase tracking-wider text-slate-500">
                                <tr>
                                    <th class="py-3 px-4">Nama Part</th>
                                    <th class="py-3 px-4 text-center">Harga</th>
                                    <th class="py-3 px-4 text-center">Qty</th>
                                    <th class="py-3 px-4 text-right">Subtotal</th>
                                    <th class="py-3 px-4 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 font-medium">
                                @forelse($task->details as $detail)
                                    <tr>
                                        <td class="py-3.5 px-4 font-bold text-slate-900">{{ $detail->sparepart ? $detail->sparepart->part_name : 'Part' }}</td>
                                        <td class="py-3.5 px-4 text-center">Rp {{ number_format($detail->price_at_time, 0, ',', '.') }}</td>
                                        <td class="py-3.5 px-4 text-center font-bold">{{ $detail->quantity }}x</td>
                                        <td class="py-3.5 px-4 text-right font-black text-blue-600">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                        <td class="py-3.5 px-4 text-right">
                                            <form action="{{ route('teknisi.tasks.removeSparepart', [$task->id, $detail->id]) }}" method="POST" class="inline" onsubmit="return confirm('Lepas part dari unit ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-rose-500 hover:text-rose-700 text-xs font-bold"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-6 text-center text-slate-400 text-xs">Belum ada sparepart yang dipasang.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Photos Documentation -->
                <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs space-y-6">
                    <h3 class="font-bold text-slate-900 text-base border-b border-slate-100 pb-3 flex items-center gap-2">
                        <i class="fas fa-camera text-blue-600"></i> Unggah Foto Kerusakan / Hasil Perbaikan
                    </h3>

                    <form action="{{ route('teknisi.tasks.uploadPhoto', $task->id) }}" method="POST" enctype="multipart/form-data" class="bg-slate-50 p-4 rounded-2xl border border-slate-200 space-y-3">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Tipe Dokumentasi</label>
                                <select name="photo_type" required class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs bg-white">
                                    <option value="before">Foto Sebelum Perbaikan (Kerusakan)</option>
                                    <option value="after">Foto Setelah Perbaikan (Hasil)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Unggah Foto</label>
                                <input type="file" name="image" accept="image/*" class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Keterangan Foto</label>
                                <input type="text" name="description" placeholder="Deskripsi foto..." class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs bg-white">
                            </div>
                        </div>
                        <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white font-bold px-4 py-2 rounded-xl text-xs transition">
                            Upload Dokumentasi
                        </button>
                    </form>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        @forelse($task->photos as $photo)
                            <div class="relative group rounded-2xl overflow-hidden border border-slate-200 bg-slate-100 aspect-square">
                                <img src="{{ $photo->image }}" class="w-full h-full object-cover group-hover:scale-105 transition">
                                <div class="absolute inset-0 bg-slate-900/60 opacity-0 group-hover:opacity-100 transition p-3 flex flex-col justify-between text-white text-xs">
                                    <span class="font-bold capitalize bg-white/20 px-2 py-0.5 rounded-md w-fit">
                                        {{ $photo->photo_type === 'before' ? 'Sebelum' : 'Sesudah' }}
                                    </span>
                                    <div>
                                        <p class="text-[11px] line-clamp-2">{{ $photo->description }}</p>
                                        <form action="{{ route('teknisi.tasks.deletePhoto', [$task->id, $photo->id]) }}" method="POST" class="mt-2" onsubmit="return confirm('Hapus foto?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-rose-400 hover:text-rose-200 font-bold text-[10px]"><i class="fas fa-trash"></i> Hapus</button>
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

            <!-- Right Column Info -->
            <div class="lg:col-span-4 space-y-8">
                <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs space-y-4">
                    <h3 class="font-bold text-slate-900 text-base border-b border-slate-100 pb-3 flex items-center gap-2">
                        <i class="fas fa-info-circle text-blue-600"></i> Detail Keluhan Customer
                    </h3>
                    <div class="space-y-3 text-xs">
                        <div>
                            <span class="text-slate-400 font-bold uppercase block">Keluhan:</span>
                            <p class="font-medium text-slate-800 mt-0.5 bg-slate-50 p-3 rounded-xl border border-slate-100 leading-relaxed">{{ $task->complaint }}</p>
                        </div>
                        <div>
                            <span class="text-slate-400 font-bold uppercase block">Kelengkapan:</span>
                            <p class="font-bold text-slate-800 mt-0.5">{{ $task->equipment ?? 'Tidak Ada' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
