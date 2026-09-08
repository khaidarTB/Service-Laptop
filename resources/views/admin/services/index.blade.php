<x-app-layout>
    <x-slot name="header">Data Tiket Servis Laptop</x-slot>

    <div class="space-y-6" x-data="{ open: false }">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-2 overflow-x-auto pb-2 sm:pb-0">
                <a href="{{ route('admin.services.index') }}" class="px-3.5 py-2 rounded-2xl text-xs font-bold whitespace-nowrap transition {{ !$status ? 'bg-blue-600 text-white' : 'bg-white text-slate-600 border border-slate-200' }}">Semua Status</a>
                @foreach($statuses as $st)
                    @php
                        $badgeStyle = match($st) {
                            'antrean' => 'bg-slate-100 text-slate-700',
                            'pemeriksaan' => 'bg-amber-50 text-amber-700 border-amber-200',
                            'menunggu_persetujuan' => 'bg-orange-50 text-orange-700 border-orange-200',
                            'perbaikan' => 'bg-blue-50 text-blue-700 border-blue-200',
                            'selesai' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            'diambil' => 'bg-purple-50 text-purple-700 border-purple-200',
                            'batal' => 'bg-rose-50 text-rose-700 border-rose-200',
                            default => 'bg-slate-100 text-slate-700'
                        };
                    @endphp
                    <a href="{{ route('admin.services.index', ['status' => $st]) }}"
                       class="px-3 py-1.5 rounded-2xl text-xs font-bold border capitalize whitespace-nowrap transition {{ $status === $st ? 'bg-slate-900 text-white border-slate-900' : $badgeStyle }}">
                        {{ str_replace('_', ' ', $st) }}
                    </a>
                @endforeach
            </div>

            <button @click="open = true" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-2xl font-bold text-sm transition shadow-lg shadow-blue-600/30 flex items-center justify-center gap-2 whitespace-nowrap">
                <i class="fas fa-plus"></i> + Buat Tiket Servis
            </button>
        </div>

        <!-- Search Bar -->
        <form action="{{ route('admin.services.index') }}" method="GET" class="max-w-md">
            <div class="relative">
                <i class="fas fa-search absolute left-3.5 top-3 text-slate-400"></i>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari No. Tiket, Merek, Customer, WhatsApp..."
                       class="w-full pl-10 pr-4 py-2.5 rounded-2xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-sm font-medium bg-white">
            </div>
        </form>

        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-700">
                    <thead class="bg-slate-50 border-b border-slate-100 text-xs font-bold uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="py-4 px-6">No Tiket & Laptop</th>
                            <th class="py-4 px-6">Pelanggan</th>
                            <th class="py-4 px-6">Teknisi</th>
                            <th class="py-4 px-6">Status Perbaikan</th>
                            <th class="py-4 px-6">Est. Biaya Total</th>
                            <th class="py-4 px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @forelse($services as $s)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-4 px-6">
                                    <div class="space-y-0.5">
                                        <a href="{{ route('admin.services.show', $s->id) }}" class="font-extrabold text-blue-600 hover:underline text-sm">
                                            {{ $s->ticket_number }}
                                        </a>
                                        <p class="font-bold text-slate-900 text-sm">{{ $s->laptop_brand }} {{ $s->laptop_type }}</p>
                                        <p class="text-xs text-slate-400">Masuk: {{ $s->date_received ? $s->date_received->format('d M Y') : '-' }}</p>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <div>
                                        <p class="font-bold text-slate-900">{{ $s->customer ? $s->customer->name : 'N/A' }}</p>
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $s->customer ? $s->customer->whatsapp : '') }}" target="_blank" class="text-xs text-blue-600 font-semibold hover:underline">
                                            <i class="fab fa-whatsapp text-emerald-500"></i> {{ $s->customer ? $s->customer->whatsapp : '-' }}
                                        </a>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    @if($s->technician)
                                        <span class="bg-blue-50 text-blue-700 text-xs font-bold px-2.5 py-1 rounded-xl border border-blue-100">
                                            <i class="fas fa-user-gear mr-1"></i> {{ $s->technician->name }}
                                        </span>
                                    @else
                                        <span class="text-xs text-slate-400 italic">Belum Ditunjuk</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6">
                                    <span class="{{ $s->status_badge_class }} px-3 py-1 rounded-xl text-xs font-extrabold shadow-2xs border">
                                        {{ $s->status_label }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 font-black text-slate-900">
                                    Rp {{ number_format($s->total_cost, 0, ',', '.') }}
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.services.show', $s->id) }}" class="inline-flex items-center gap-1 bg-slate-100 hover:bg-blue-600 hover:text-white px-3 py-2 rounded-xl text-xs font-bold transition">
                                            Lihat <i class="fas fa-chevron-right text-[10px]"></i>
                                        </a>
                                        <a href="{{ route('admin.services.edit', $s->id) }}" class="inline-flex items-center gap-1 bg-slate-100 hover:bg-amber-500 hover:text-white px-3 py-2 rounded-xl text-xs font-bold transition">
                                            <i class="fas fa-pen text-[10px]"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.services.destroy', $s->id) }}" method="POST" class="inline" onsubmit="return false;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="deleteService(this)" class="inline-flex items-center gap-1 bg-slate-100 hover:bg-rose-600 hover:text-white px-3 py-2 rounded-xl text-xs font-bold transition text-rose-600">
                                                <i class="fas fa-trash text-[10px]"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-slate-400">Belum ada tiket servis.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $services->links() }}
            </div>
        </div>

        <!-- Create Modal -->
        <div x-show="open" x-transition.opacity class="fixed inset-0 z-50 hidden">
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="open = false"></div>
            <div class="fixed inset-0 flex items-center justify-center p-4">
                <div class="bg-white rounded-3xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto p-6" @click.stop>
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-black text-slate-900">Buat Tiket Servis Baru</h2>
                        <button @click="open = false" class="text-slate-400 hover:text-slate-600 text-xl"><i class="fas fa-xmark"></i></button>
                    </div>

                    <form action="{{ route('admin.services.store') }}" method="POST" class="space-y-5">
                        @csrf

                        <div class="flex justify-between items-center p-4 rounded-2xl bg-blue-50 border border-blue-200">
                            <span class="text-xs font-bold text-blue-900 uppercase tracking-wider">Nomor Tiket Otomatis</span>
                            <span class="text-lg font-black text-blue-700">{{ $ticketNumber }}</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Pilih Customer *</label>
                                <select name="customer_id" required class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-sm font-medium bg-white">
                                    <option value="">-- Pilih Customer --</option>
                                    @foreach($customers as $c)
                                        <option value="{{ $c->id }}">{{ $c->name }} (WA: {{ $c->whatsapp }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tunjuk Teknisi Penanggung Jawab</label>
                                <select name="assigned_technician_id" class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-sm font-medium bg-white">
                                    <option value="">-- Belum Ditunjuk --</option>
                                    @foreach($technicians as $t)
                                        <option value="{{ $t->id }}">{{ $t->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Merek Laptop *</label>
                                <input type="text" name="laptop_brand" required placeholder="Asus / Lenovo / Acer..." class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm font-medium">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tipe / Seri Laptop *</label>
                                <input type="text" name="laptop_type" required placeholder="TUF Gaming A15..." class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm font-medium">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Serial Number (SN)</label>
                                <input type="text" name="serial_number" placeholder="SN12345678" class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm font-medium">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kelengkapan Dititipkan</label>
                                <input type="text" name="equipment" placeholder="Charger, Tas, Mouse..." class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm font-medium">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Keluhan Kerusakan *</label>
                            <textarea name="complaint" required rows="3" placeholder="Deskripsi keluhan pelanggan..." class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm font-medium"></textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Biaya Jasa Servis (Rp)</label>
                                <input type="number" name="service_fee" value="50000" class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm font-medium">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Estimasi Biaya Total (Rp)</label>
                                <input type="number" name="estimated_cost" placeholder="150000" class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm font-medium">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Status Awal</label>
                                <select name="status" class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm font-medium bg-white">
                                    <option value="antrean">Antrean (Masuk Servis)</option>
                                    <option value="pemeriksaan">Pemeriksaan (Diagnosa)</option>
                                </select>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-slate-100 flex gap-4">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-3 rounded-2xl text-sm transition">Buat Tiket Servis</button>
                            <button type="button" @click="open = false" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold px-6 py-3 rounded-2xl text-sm transition">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function deleteService(form) {
            Swal.fire({
                title: 'Hapus Tiket Servis?',
                text: 'Data tiket servis yang dihapus tidak dapat dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>
</x-app-layout>
