<x-app-layout>
    <x-slot name="header">Katalog Sparepart & Stok Inventory</x-slot>

    <div class="space-y-6" x-data="sparepartModal()">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <form action="{{ route('admin.spareparts.index') }}" method="GET" class="flex items-center gap-2 max-w-md flex-1">
                <div class="relative w-full">
                    <i class="fas fa-search absolute left-3.5 top-3 text-slate-400"></i>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama part..."
                           class="w-full pl-10 pr-4 py-2.5 rounded-2xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-sm font-medium bg-white">
                </div>
                <button type="submit" class="px-4 py-2.5 bg-slate-800 text-white rounded-2xl text-sm font-bold hover:bg-slate-900 transition">Cari</button>
            </form>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.spareparts.index', ['low_stock' => 1]) }}" class="px-4 py-2.5 rounded-2xl text-xs font-bold transition flex items-center gap-1.5 {{ $lowStock ? 'bg-rose-600 text-white shadow-lg shadow-rose-600/30' : 'bg-white text-rose-600 border border-rose-200' }}">
                    <i class="fas fa-triangle-exclamation"></i> Stok Menipis
                </a>
                <button @click="resetForm(); mode='create'; open=true" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-2xl font-bold text-sm transition shadow-lg shadow-blue-600/30 flex items-center justify-center gap-2">
                    <i class="fas fa-plus"></i> + Tambah Sparepart
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($spareparts as $part)
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-5 flex flex-col justify-between space-y-4 hover:border-blue-300 transition group">
                    <div class="space-y-3">
                        <div class="aspect-video w-full rounded-2xl overflow-hidden bg-slate-100 relative">
                            <img src="{{ $part->image ?? 'https://images.unsplash.com/photo-1597872200969-2b65d56bd16b?w=400' }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            @if($part->stock <= $part->min_stock)
                                <span class="absolute top-3 right-3 bg-rose-500 text-white text-[10px] font-black px-2.5 py-1 rounded-xl shadow-xs animate-pulse">
                                    STOK MENIPIS!
                                </span>
                            @endif
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 text-base group-hover:text-blue-600 transition">{{ $part->part_name }}</h3>
                            <p class="text-xs text-slate-400 line-clamp-2 mt-0.5">{{ $part->description ?? 'Tidak ada deskripsi.' }}</p>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 space-y-3">
                        <div class="grid grid-cols-2 gap-2 text-xs">
                            <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                                <span class="text-slate-400 block text-[10px] font-bold uppercase">Harga Beli</span>
                                <span class="font-bold text-slate-700">Rp {{ number_format($part->cost_price, 0, ',', '.') }}</span>
                            </div>
                            <div class="bg-blue-50/60 p-2.5 rounded-xl border border-blue-100">
                                <span class="text-blue-500 block text-[10px] font-bold uppercase">Harga Jual</span>
                                <span class="font-black text-blue-700">Rp {{ number_format($part->selling_price, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold text-slate-500">
                                Stok: <b class="{{ $part->stock <= $part->min_stock ? 'text-rose-600' : 'text-slate-900' }}">{{ $part->stock }}</b> (Min: {{ $part->min_stock }})
                            </span>

                            <div class="flex items-center gap-1">
                                <button @click="editPart({{ json_encode($part) }})" class="p-2 text-slate-400 hover:text-blue-600 transition"><i class="fas fa-edit"></i></button>
                                <button type="button" @click="confirmDelete({{ $part->id }}, '{{ $part->part_name }}')" class="p-2 text-slate-400 hover:text-rose-600 transition"><i class="fas fa-trash"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white p-12 rounded-3xl border border-slate-200/80 text-center text-slate-400">
                    Belum ada data sparepart.
                </div>
            @endforelse
        </div>

        <div class="pt-4">
            {{ $spareparts->links() }}
        </div>

        {{-- Create / Edit Modal --}}
        <div x-show="open" x-transition.opacity x-cloak class="fixed inset-0 z-50">
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="open=false"></div>
            <div class="fixed inset-0 flex items-center justify-center p-4">
                <div class="bg-white rounded-3xl shadow-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto p-6" @click.stop x-init="$watch('open', v => { if(v) $nextTick(() => {}) })">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-black text-slate-900" x-text="mode === 'create' ? 'Tambah Sparepart Baru' : 'Edit Data Sparepart'"></h2>
                        <button @click="open=false" class="p-2 text-slate-400 hover:text-slate-600 transition rounded-xl hover:bg-slate-100">
                            <i class="fas fa-xmark text-lg"></i>
                        </button>
                    </div>

                    <form :action="mode === 'create' ? '{{ route('admin.spareparts.store') }}' : '{{ route('admin.spareparts.update', '__ID__') }}'.replace('__ID__', part.id || '')"
                          method="POST" enctype="multipart/form-data" class="space-y-5">
                        @csrf
                        <template x-if="mode === 'edit'">
                            <input type="hidden" name="_method" value="PUT">
                        </template>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Sparepart *</label>
                            <input type="text" name="part_name" x-model="part.part_name" required placeholder="RAM DDR4 8GB Kingston 3200MHz"
                                   class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-sm font-medium">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Deskripsi Singkat</label>
                            <textarea name="description" x-model="part.description" rows="2"
                                      class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-sm font-medium"></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Stok Saat Ini *</label>
                                <input type="number" name="stock" x-model.number="part.stock" required min="0"
                                       class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm font-medium">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Stok Minimum *</label>
                                <input type="number" name="min_stock" x-model.number="part.min_stock" required min="0"
                                       class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm font-medium">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Harga Beli / Modal (Rp) *</label>
                                <input type="number" name="cost_price" x-model.number="part.cost_price" required placeholder="300000"
                                       class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm font-medium">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Harga Jual (Rp) *</label>
                                <input type="number" name="selling_price" x-model.number="part.selling_price" required placeholder="450000"
                                       class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm font-medium">
                            </div>
                        </div>

                        <div class="space-y-3 pt-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider" x-text="mode === 'edit' ? 'Ganti Gambar Sparepart' : 'Unggah Gambar Sparepart (Opsional)'"></label>
                            <input type="file" name="image" accept="image/*"
                                   class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <p class="text-[11px] text-slate-400">Atau masukkan URL Foto Gambar:</p>
                            <input type="url" name="image_url" x-model="part.image" placeholder="https://..."
                                   class="w-full px-4 py-2 rounded-xl border border-slate-200 text-xs">
                        </div>

                        <div class="pt-4 border-t border-slate-100 flex gap-4">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-3 rounded-2xl text-sm transition"
                                    x-text="mode === 'create' ? 'Simpan Sparepart' : 'Perbarui Sparepart'"></button>
                            <button type="button" @click="open=false" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold px-6 py-3 rounded-2xl text-sm transition">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function sparepartModal() {
            return {
                open: false,
                mode: 'create',
                part: {},
                resetForm() {
                    this.part = { part_name: '', description: '', stock: 10, min_stock: 2, cost_price: '', selling_price: '', image: '' };
                },
                editPart(data) {
                    this.mode = 'edit';
                    this.part = { ...data };
                    this.open = true;
                },
                confirmDelete(id, name) {
                    Swal.fire({
                        title: 'Hapus Sparepart?',
                        html: `Apakah Anda yakin ingin menghapus <b>${name}</b>?`,
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
                            form.action = `{{ url('admin/spareparts') }}/${id}`;
                            form.innerHTML = `
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="_method" value="DELETE">
                            `;
                            document.body.appendChild(form);
                            form.submit();
                        }
                    });
                }
            };
        }
    </script>
    @endpush
</x-app-layout>
