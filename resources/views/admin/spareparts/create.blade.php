<x-app-layout>
    <x-slot name="header">Tambah Sparepart Baru</x-slot>

    <div class="max-w-2xl mx-auto bg-white p-8 rounded-3xl border border-slate-200/80 shadow-xs">
        <form action="{{ route('admin.spareparts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Sparepart *</label>
                    <input type="text" name="part_name" required placeholder="RAM DDR4 8GB Kingston 3200MHz" class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-sm font-medium">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Deskripsi Singkat</label>
                    <textarea name="description" rows="2" class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-sm font-medium"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Stok Saat Ini *</label>
                        <input type="number" name="stock" value="10" required min="0" class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm font-medium">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Stok Minimum Peringatan *</label>
                        <input type="number" name="min_stock" value="2" required min="0" class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm font-medium">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Harga Beli / Modal (Rp) *</label>
                        <input type="number" name="cost_price" required placeholder="300000" class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm font-medium">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Harga Jual (Rp) *</label>
                        <input type="number" name="selling_price" required placeholder="450000" class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm font-medium">
                    </div>
                </div>

                <div class="space-y-3 pt-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Unggah Gambar Sparepart (Opsional)</label>
                    <input type="file" name="image" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="text-[11px] text-slate-400">Atau masukkan URL Foto Gambar:</p>
                    <input type="url" name="image_url" placeholder="https://..." class="w-full px-4 py-2 rounded-xl border border-slate-200 text-xs">
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex gap-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-3 rounded-2xl text-sm transition">Simpan Sparepart</button>
                <a href="{{ route('admin.spareparts.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold px-6 py-3 rounded-2xl text-sm transition">Batal</a>
            </div>
        </form>
    </div>
</x-app-layout>
