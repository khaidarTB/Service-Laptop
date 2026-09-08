<x-app-layout>
    <x-slot name="header">Workbench Perbaikan: {{ $task->ticket_number }}</x-slot>

    <div class="space-y-8">
        {{-- Top Info Header --}}
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div class="space-y-1">
                <div class="flex items-center gap-3">
                    <h2 class="text-2xl font-black text-slate-900">{{ $task->laptop_brand }} {{ $task->laptop_type }}</h2>
                    <span class="{{ $task->status_badge_class }} px-3.5 py-1 rounded-xl text-xs font-extrabold border">
                        {{ $task->status_label }}
                    </span>
                </div>
                <p class="text-xs text-slate-500 font-medium">
                    Customer: <b>{{ $task->customer?->name ?? '-' }}</b> • SN: {{ $task->serial_number ?? 'N/A' }} • Kelengkapan: {{ $task->equipment ?? 'Tidak Ada' }}
                </p>
            </div>

            <div class="flex items-center gap-2 flex-wrap">
                @if($task->customer?->whatsapp)
                    <form action="{{ route('teknisi.tasks.updateStatus', $task->id) }}" method="POST" id="waAutoSendForm" class="hidden">
                        @csrf
                        <input type="hidden" name="status" value="{{ $task->status }}">
                    </form>
                    <button type="button"
                            x-data
                            @click="
                                Swal.fire({
                                    title: 'Kirim Notifikasi WA?',
                                    text: 'Kirim pembaruan status ke {{ $task->customer->name }} via WhatsApp.',
                                    icon: 'question',
                                    showCancelButton: true,
                                    confirmButtonColor: '#059669',
                                    cancelButtonColor: '#64748b',
                                    confirmButtonText: '<i class=\'fab fa-whatsapp mr-1\'></i> Kirim WA',
                                    cancelButtonText: 'Batal'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        fetch('{{ route('teknisi.tasks.updateStatus', $task->id) }}', {
                                            method: 'POST',
                                            headers: {
                                                'Content-Type': 'application/json',
                                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                'Accept': 'application/json'
                                            },
                                            body: JSON.stringify({
                                                status: '{{ $task->status }}',
                                                notes: 'Notifikasi WhatsApp dikirim via sistem'
                                            })
                                        }).then(() => {
                                            Swal.fire({ title: 'Terkirim!', text: 'Notifikasi WhatsApp berhasil dikirim.', icon: 'success', timer: 2000, showConfirmButton: false });
                                        }).catch(() => {
                                            Swal.fire({ title: 'Gagal', text: 'Gagal mengirim notifikasi.', icon: 'error' });
                                        });
                                    }
                                })
                            "
                            class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-4 py-2.5 rounded-2xl text-xs transition shadow-md shadow-emerald-600/20 flex items-center gap-1.5">
                        <i class="fab fa-whatsapp text-lg"></i> Kirim WA Customer
                    </button>
                @endif
            </div>
        </div>

        {{-- Status Update: Dark Card with Modal Trigger --}}
        <div class="bg-slate-900 text-white p-6 sm:p-8 rounded-3xl shadow-xl" x-data="{ open: false }">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <h3 class="font-extrabold text-lg flex items-center gap-2">
                        <i class="fas fa-sliders text-blue-400"></i> Update Status Perbaikan Unit
                    </h3>
                    <p class="text-slate-400 text-xs mt-1">Status saat ini: <span class="font-bold text-blue-400">{{ $task->status_label }}</span></p>
                </div>
                <button @click="open = true" class="bg-blue-600 hover:bg-blue-700 text-white font-extrabold px-6 py-3 rounded-2xl text-sm transition shadow-lg shadow-blue-600/30 flex items-center gap-2">
                    <i class="fas fa-pen-to-square"></i> Update Status
                </button>
            </div>

            <div x-show="open" x-transition.opacity class="fixed inset-0 z-50">
                <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="open = false"></div>
                <div class="fixed inset-0 flex items-center justify-center p-4">
                    <div class="bg-white rounded-3xl shadow-2xl max-w-lg w-full p-6" @click.stop>
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-extrabold text-slate-900">Update Status Perbaikan</h3>
                            <button @click="open = false" class="text-slate-400 hover:text-slate-600"><i class="fas fa-times text-xl"></i></button>
                        </div>
                        <form action="{{ route('teknisi.tasks.updateStatus', $task->id) }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Pilih Status Terbaru</label>
                                <select name="status" required class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm font-semibold bg-slate-50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition">
                                    <option value="antrean" {{ $task->status === 'antrean' ? 'selected' : '' }}>Antrean (Masuk Servis)</option>
                                    <option value="pemeriksaan" {{ $task->status === 'pemeriksaan' ? 'selected' : '' }}>Pemeriksaan (Diagnosa Kerusakan)</option>
                                    <option value="menunggu_persetujuan" {{ $task->status === 'menunggu_persetujuan' ? 'selected' : '' }}>Menunggu Persetujuan Customer</option>
                                    <option value="perbaikan" {{ $task->status === 'perbaikan' ? 'selected' : '' }}>Dalam Perbaikan</option>
                                    <option value="selesai" {{ $task->status === 'selesai' ? 'selected' : '' }}>Servis Selesai (Siap Diambil)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Catatan Pengerjaan Teknisi</label>
                                <input type="text" name="notes" placeholder="Tuliskan perkembangan perbaikan..." class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm bg-slate-50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition">
                            </div>
                            <div class="flex gap-3 pt-2">
                                <button type="button" @click="open = false" class="flex-1 px-4 py-3 rounded-2xl border border-slate-200 text-sm font-bold text-slate-600 hover:bg-slate-50 transition">Batal</button>
                                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-extrabold px-4 py-3 rounded-2xl text-sm transition shadow-lg shadow-blue-600/30">
                                    Simpan Status
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            {{-- Left Side --}}
            <div class="lg:col-span-8 space-y-8">

                {{-- Diagnosis Form with Confirmation Modal --}}
                <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs space-y-6" x-data="{ confirmDiag: false }">
                    <h3 class="font-bold text-slate-900 text-base border-b border-slate-100 pb-3 flex items-center gap-2">
                        <i class="fas fa-stethoscope text-blue-600"></i> Form Diagnosa & Jasa Teknisi
                    </h3>

                    <form id="diagnosisForm" action="{{ route('teknisi.tasks.update', $task->id) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Hasil Diagnosa Kerusakan *</label>
                            <textarea name="diagnosis" required rows="4" placeholder="Jelaskan kerusakan teknis yang ditemukan setelah pemeriksaan..." class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm font-medium focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition">{{ old('diagnosis', $task->diagnosis) }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Biaya Jasa Servis (Rp)</label>
                                <input type="number" name="service_fee" value="{{ old('service_fee', $task->service_fee) }}" class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm font-medium focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Estimasi Biaya Total (Rp)</label>
                                <input type="number" name="estimated_cost" value="{{ old('estimated_cost', $task->estimated_cost) }}" class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm font-medium focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition">
                            </div>
                        </div>

                        <button type="button" @click="confirmDiag = true" class="bg-slate-900 hover:bg-slate-800 text-white font-bold px-6 py-3 rounded-2xl text-sm transition">
                            Simpan Diagnosa & Biaya
                        </button>
                    </form>

                    <div x-show="confirmDiag" x-transition.opacity class="fixed inset-0 z-50">
                        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="confirmDiag = false"></div>
                        <div class="fixed inset-0 flex items-center justify-center p-4">
                            <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-6" @click.stop>
                                <div class="text-center space-y-3">
                                    <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto">
                                        <i class="fas fa-stethoscope text-blue-600 text-2xl"></i>
                                    </div>
                                    <h3 class="text-lg font-extrabold text-slate-900">Simpan Diagnosa?</h3>
                                    <p class="text-sm text-slate-500">Pastikan data diagnosa dan biaya sudah benar sebelum disimpan.</p>
                                </div>
                                <div class="flex gap-3 mt-6">
                                    <button type="button" @click="confirmDiag = false" class="flex-1 px-4 py-3 rounded-2xl border border-slate-200 text-sm font-bold text-slate-600 hover:bg-slate-50 transition">Batal</button>
                                    <button type="button" @click="document.getElementById('diagnosisForm').submit(); confirmDiag = false" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-extrabold px-4 py-3 rounded-2xl text-sm transition">Ya, Simpan</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Sparepart Section --}}
                <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs space-y-6" x-data="{ open: false }">
                    <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                        <h3 class="font-bold text-slate-900 text-base flex items-center gap-2">
                            <i class="fas fa-box-open text-blue-600"></i> Pasang Sparepart / Komponen
                        </h3>
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-black text-blue-700 bg-blue-50 px-3 py-1 rounded-xl border border-blue-200">
                                Total Part: Rp {{ number_format($task->details->sum('subtotal'), 0, ',', '.') }}
                            </span>
                            <button @click="open = true" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-4 py-2 rounded-xl text-xs transition flex items-center gap-1.5">
                                <i class="fas fa-plus"></i> Tambah Part
                            </button>
                        </div>
                    </div>

                    <div x-show="open" x-transition.opacity class="fixed inset-0 z-50">
                        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="open = false"></div>
                        <div class="fixed inset-0 flex items-center justify-center p-4">
                            <div class="bg-white rounded-3xl shadow-2xl max-w-lg w-full p-6" @click.stop>
                                <div class="flex items-center justify-between mb-6">
                                    <h3 class="text-lg font-extrabold text-slate-900">Tambah Sparepart</h3>
                                    <button @click="open = false" class="text-slate-400 hover:text-slate-600"><i class="fas fa-times text-xl"></i></button>
                                </div>
                                <form action="{{ route('teknisi.tasks.addSparepart', $task->id) }}" method="POST" class="space-y-4">
                                    @csrf
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Pilih Part dari Stok Inventory</label>
                                        <select name="sparepart_id" required class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm font-medium bg-slate-50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition">
                                            <option value="">-- Pilih Sparepart --</option>
                                            @foreach($spareparts as $part)
                                                <option value="{{ $part->id }}">{{ $part->part_name }} (Stok: {{ $part->stock }} - Rp {{ number_format($part->selling_price, 0, ',', '.') }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Jumlah Quantity</label>
                                        <input type="number" name="quantity" value="1" min="1" required class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm font-medium bg-slate-50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition">
                                    </div>
                                    <div class="flex gap-3 pt-2">
                                        <button type="button" @click="open = false" class="flex-1 px-4 py-3 rounded-2xl border border-slate-200 text-sm font-bold text-slate-600 hover:bg-slate-50 transition">Batal</button>
                                        <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-extrabold px-4 py-3 rounded-2xl text-sm transition shadow-lg shadow-blue-600/30 flex items-center justify-center gap-1.5">
                                            <i class="fas fa-plus"></i> Pasang Part
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

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
                                        <td class="py-3.5 px-4 font-bold text-slate-900">{{ $detail->sparepart?->part_name ?? 'Part' }}</td>
                                        <td class="py-3.5 px-4 text-center">Rp {{ number_format($detail->price_at_time, 0, ',', '.') }}</td>
                                        <td class="py-3.5 px-4 text-center font-bold">{{ $detail->quantity }}x</td>
                                        <td class="py-3.5 px-4 text-right font-black text-blue-600">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                        <td class="py-3.5 px-4 text-right" x-data>
                                            <button type="button"
                                                    @click="
                                                        Swal.fire({
                                                            title: 'Hapus Part?',
                                                            text: '{{ $detail->sparepart?->part_name }} akan dilepas dari tiket ini. Stok akan dikembalikan.',
                                                            icon: 'warning',
                                                            showCancelButton: true,
                                                            confirmButtonColor: '#dc2626',
                                                            cancelButtonColor: '#64748b',
                                                            confirmButtonText: 'Ya, Hapus!',
                                                            cancelButtonText: 'Batal'
                                                        }).then((result) => {
                                                            if (result.isConfirmed) {
                                                                const form = document.createElement('form');
                                                                form.method = 'POST';
                                                                form.action = '{{ route('teknisi.tasks.removeSparepart', [$task->id, $detail->id]) }}';
                                                                form.innerHTML = '<input type=\"hidden\" name=\"_token\" value=\"{{ csrf_token() }}\"><input type=\"hidden\" name=\"_method\" value=\"DELETE\">';
                                                                document.body.appendChild(form);
                                                                form.submit();
                                                            }
                                                        })
                                                    "
                                                    class="text-rose-500 hover:text-rose-700 text-xs font-bold">
                                                <i class="fas fa-trash"></i>
                                            </button>
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

                {{-- Photo Upload Section --}}
                <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs space-y-6" x-data="{ open: false }">
                    <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                        <h3 class="font-bold text-slate-900 text-base flex items-center gap-2">
                            <i class="fas fa-camera text-blue-600"></i> Dokumentasi Foto Kerusakan / Hasil Perbaikan
                        </h3>
                        <button @click="open = true" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-4 py-2 rounded-xl text-xs transition flex items-center gap-1.5">
                            <i class="fas fa-cloud-arrow-up"></i> Upload Foto
                        </button>
                    </div>

                    <div x-show="open" x-transition.opacity class="fixed inset-0 z-50">
                        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="open = false"></div>
                        <div class="fixed inset-0 flex items-center justify-center p-4">
                            <div class="bg-white rounded-3xl shadow-2xl max-w-lg w-full p-6" @click.stop>
                                <div class="flex items-center justify-between mb-6">
                                    <h3 class="text-lg font-extrabold text-slate-900">Upload Dokumentasi Foto</h3>
                                    <button @click="open = false" class="text-slate-400 hover:text-slate-600"><i class="fas fa-times text-xl"></i></button>
                                </div>
                                <form action="{{ route('teknisi.tasks.uploadPhoto', $task->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                    @csrf
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tipe Dokumentasi</label>
                                        <select name="photo_type" required class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm bg-slate-50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition">
                                            <option value="before">Foto Sebelum Perbaikan (Kerusakan)</option>
                                            <option value="after">Foto Setelah Perbaikan (Hasil)</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Unggah Foto</label>
                                        <input type="file" name="image" accept="image/*" class="w-full text-sm text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">URL Foto (Opsional)</label>
                                        <input type="url" name="image_url" placeholder="https://..." class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm bg-slate-50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Keterangan Foto</label>
                                        <input type="text" name="description" placeholder="Deskripsi foto..." class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm bg-slate-50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition">
                                    </div>
                                    <div class="flex gap-3 pt-2">
                                        <button type="button" @click="open = false" class="flex-1 px-4 py-3 rounded-2xl border border-slate-200 text-sm font-bold text-slate-600 hover:bg-slate-50 transition">Batal</button>
                                        <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-extrabold px-4 py-3 rounded-2xl text-sm transition shadow-lg shadow-blue-600/30 flex items-center justify-center gap-1.5">
                                            <i class="fas fa-cloud-arrow-up"></i> Upload
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        @forelse($task->photos as $photo)
                            <div class="relative group rounded-2xl overflow-hidden border border-slate-200 bg-slate-100 aspect-square">
                                <img src="{{ $photo->image }}" class="w-full h-full object-cover group-hover:scale-105 transition" alt="Foto dokumentasi">
                                <div class="absolute inset-0 bg-slate-900/60 opacity-0 group-hover:opacity-100 transition p-3 flex flex-col justify-between text-white text-xs">
                                    <span class="font-bold capitalize bg-white/20 px-2 py-0.5 rounded-md w-fit backdrop-blur-sm">
                                        {{ $photo->photo_type === 'before' ? 'Sebelum' : 'Sesudah' }}
                                    </span>
                                    <div>
                                        <p class="text-[11px] line-clamp-2">{{ $photo->description }}</p>
                                        <button type="button" x-data
                                                @click="
                                                    Swal.fire({
                                                        title: 'Hapus Foto?',
                                                        text: 'Foto dokumentasi ini akan dihapus secara permanen.',
                                                        icon: 'warning',
                                                        showCancelButton: true,
                                                        confirmButtonColor: '#dc2626',
                                                        cancelButtonColor: '#64748b',
                                                        confirmButtonText: 'Ya, Hapus!',
                                                        cancelButtonText: 'Batal'
                                                    }).then((result) => {
                                                        if (result.isConfirmed) {
                                                            const form = document.createElement('form');
                                                            form.method = 'POST';
                                                            form.action = '{{ route('teknisi.tasks.deletePhoto', [$task->id, $photo->id]) }}';
                                                            form.innerHTML = '<input type=\"hidden\" name=\"_token\" value=\"{{ csrf_token() }}\"><input type=\"hidden\" name=\"_method\" value=\"DELETE\">';
                                                            document.body.appendChild(form);
                                                            form.submit();
                                                        }
                                                    })
                                                "
                                                class="mt-2 text-rose-400 hover:text-rose-200 font-bold text-[10px]">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full py-6 text-center text-slate-400 text-xs">Belum ada foto dokumentasi.</div>
                        @endforelse
                    </div>
                </div>

                {{-- Diskusi / Komentar Tiket --}}
                <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs space-y-6">
                    <h3 class="font-bold text-slate-900 text-base border-b border-slate-100 pb-3 flex items-center gap-2">
                        <i class="fas fa-comments text-blue-600"></i> Diskusi / Komentar Tiket
                        <span class="text-xs font-bold text-slate-400 bg-slate-100 px-2 py-0.5 rounded-lg">{{ $task->comments->count() }}</span>
                    </h3>

                    {{-- Chat Bubbles --}}
                    <div class="space-y-4 max-h-[480px] overflow-y-auto pr-1" id="commentsContainer">
                        @forelse($task->comments->filter(fn($c) => !$c->is_internal) as $comment)
                            @php
                                $isOwn = $comment->user_id === auth()->id();
                            @endphp
                            <div class="flex gap-3 {{ $isOwn ? 'flex-row-reverse' : '' }}">
                                {{-- Avatar --}}
                                <div class="flex-shrink-0 w-9 h-9 rounded-full flex items-center justify-center text-xs font-extrabold
                                    {{ $isOwn ? 'bg-blue-600 text-white' : ($comment->user?->role === 'customer' ? 'bg-amber-100 text-amber-700' : 'bg-slate-200 text-slate-600') }}">
                                    {{ strtoupper(substr($comment->user?->name ?? '?', 0, 1)) }}
                                </div>

                                {{-- Bubble --}}
                                <div class="max-w-[75%] space-y-1">
                                    <div class="flex items-center gap-2 {{ $isOwn ? 'justify-end' : '' }}">
                                        <span class="text-xs font-bold text-slate-800">{{ $comment->user?->name ?? 'Unknown' }}</span>
                                        <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-md
                                            {{ $comment->user?->role === 'admin' ? 'bg-purple-100 text-purple-700' : ($comment->user?->role === 'teknisi' ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700') }}">
                                            {{ ucfirst($comment->user?->role ?? 'user') }}
                                        </span>
                                    </div>
                                    <div class="{{ $isOwn ? 'bg-blue-600 text-white rounded-2xl rounded-tr-md' : 'bg-slate-100 text-slate-800 rounded-2xl rounded-tl-md' }} px-4 py-3 text-sm leading-relaxed">
                                        {!! nl2br(e($comment->message)) !!}
                                    </div>
                                    <p class="text-[10px] text-slate-400 font-medium {{ $isOwn ? 'text-right' : '' }}">{{ $comment->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="py-8 text-center text-slate-400 text-xs">
                                <i class="fas fa-comment-slash text-2xl mb-2 text-slate-300"></i>
                                <p>Belum ada diskusi. Mulai percakapan di bawah.</p>
                            </div>
                        @endforelse
                    </div>

                    {{-- Comment Form --}}
                    <form action="{{ route('comments.store', $task->id) }}" method="POST" class="border-t border-slate-100 pt-4">
                        @csrf
                        <div class="flex gap-3 items-end">
                            <div class="w-9 h-9 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-extrabold flex-shrink-0">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <div class="flex-1 space-y-2">
                                <textarea name="message" required rows="2" maxlength="1000"
                                          placeholder="Tulis pesan atau balasan..."
                                          class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm font-medium bg-slate-50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition resize-none"></textarea>
                                <div class="flex justify-end">
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-5 py-2.5 rounded-xl text-xs transition shadow-md shadow-blue-600/20 flex items-center gap-1.5">
                                        <i class="fas fa-paper-plane"></i> Kirim
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Right Column --}}
            <div class="lg:col-span-4 space-y-8">
                {{-- Detail Keluhan Customer --}}
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

                {{-- Approval Status --}}
                @if($task->latestApproval)
                    <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs space-y-4">
                        <h3 class="font-bold text-slate-900 text-base border-b border-slate-100 pb-3 flex items-center gap-2">
                            <i class="fas fa-clipboard-check text-blue-600"></i> Status Persetujuan Customer
                        </h3>
                        <div class="space-y-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center {{ $task->latestApproval->decision === 'approved' ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-600' }}">
                                    <i class="fas {{ $task->latestApproval->decision === 'approved' ? 'fa-circle-check' : 'fa-circle-xmark' }} text-lg"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-extrabold {{ $task->latestApproval->decision === 'approved' ? 'text-emerald-700' : 'text-rose-700' }}">
                                        {{ $task->latestApproval->decision === 'approved' ? 'Disetujui' : 'Ditolak' }}
                                    </p>
                                    <p class="text-[10px] text-slate-400 font-medium">{{ $task->latestApproval->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            @if($task->latestApproval->approved_amount)
                                <div class="bg-slate-50 rounded-xl p-3 border border-slate-100">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase">Jumlah Disetujui</span>
                                    <p class="text-sm font-extrabold text-slate-800">Rp {{ number_format($task->latestApproval->approved_amount, 0, ',', '.') }}</p>
                                </div>
                            @endif
                            @if($task->latestApproval->note)
                                <div class="bg-slate-50 rounded-xl p-3 border border-slate-100">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase">Catatan Customer</span>
                                    <p class="text-xs font-medium text-slate-700 mt-0.5">{{ $task->latestApproval->note }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Status History --}}
                @if($task->statusLogs->count())
                    <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs space-y-4">
                        <h3 class="font-bold text-slate-900 text-base border-b border-slate-100 pb-3 flex items-center gap-2">
                            <i class="fas fa-clock-rotate-left text-blue-600"></i> Riwayat Status
                        </h3>
                        <div class="space-y-3">
                            @foreach($task->statusLogs->take(5) as $log)
                                <div class="flex gap-3 items-start">
                                    <div class="w-2 h-2 rounded-full bg-blue-500 mt-2 flex-shrink-0"></div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-bold text-slate-800">
                                            {{ $log->old_status ? ucfirst(str_replace('_', ' ', $log->old_status)) . ' →' : '' }}
                                            <span class="text-blue-600">{{ ucfirst(str_replace('_', ' ', $log->new_status)) }}</span>
                                        </p>
                                        @if($log->notes)
                                            <p class="text-[11px] text-slate-500 mt-0.5 truncate">{{ $log->notes }}</p>
                                        @endif
                                        <p class="text-[10px] text-slate-400 mt-0.5">{{ $log->user?->name }} • {{ $log->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const container = document.getElementById('commentsContainer');
                if (container) {
                    container.scrollTop = container.scrollHeight;
                }
            });
        </script>
    @endpush
</x-app-layout>
