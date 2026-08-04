<x-app-layout>
    <x-slot name="header">Katalog Sparepart & Stok Inventory</x-slot>

    <div class="space-y-6">
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
                <a href="{{ route('admin.spareparts.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-2xl font-bold text-sm transition shadow-lg shadow-blue-600/30 flex items-center justify-center gap-2">
                    <i class="fas fa-plus"></i> + Tambah Sparepart
                </a>
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
                                <a href="{{ route('admin.spareparts.edit', $part->id) }}" class="p-2 text-slate-400 hover:text-blue-600 transition"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('admin.spareparts.destroy', $part->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus sparepart ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 transition"><i class="fas fa-trash"></i></button>
                                </form>
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
    </div>
</x-app-layout>
