<x-app-layout>
    <x-slot name="header">Edit Data Sparepart</x-slot>

    <div class="max-w-2xl mx-auto bg-white p-8 rounded-3xl border border-slate-200/80 shadow-xs">
        <form action="{{ route('admin.spareparts.update', $sparepart->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Sparepart *</label>
                    <input type="text" name="part_name" value="{{ old('part_name', $sparepart->part_name) }}" required class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-sm font-medium">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Deskripsi Singkat</label>
                    <textarea name="description" rows="2" class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-sm font-medium">{{ old('description', $sparepart->description) }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Stok Saat Ini *</label>
                        <input type="number" name="stock" value="{{ old('stock', $sparepart->stock) }}" required min="0" class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm font-medium">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Stok Minimum *</label>
                        <input type="number" name="min_stock" value="{{ old('min_stock', $sparepart->min_stock) }}" required min="0" class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm font-medium">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Harga Beli (Rp) *</label>
                        <input type="number" name="cost_price" value="{{ old('cost_price', $sparepart->cost_price) }}" required class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm font-medium">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Harga Jual (Rp) *</label>
                        <input type="number" name="selling_price" value="{{ old('selling_price', $sparepart->selling_price) }}" required class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm font-medium">
                    </div>
                </div>

                <div class="space-y-3 pt-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Unggah Gambar / Ganti Foto</label>
                    <input type="file" name="image" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="text-[11px] text-slate-400">Atau URL Gambar:</p>
                    <input type="url" name="image_url" value="{{ old('image_url', $sparepart->image) }}" class="w-full px-4 py-2 rounded-xl border border-slate-200 text-xs">
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex gap-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-3 rounded-2xl text-sm transition">Perbarui Sparepart</button>
                <a href="{{ route('admin.spareparts.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold px-6 py-3 rounded-2xl text-sm transition">Batal</a>
            </div>
        </form>
    </div>
</x-app-layout>
