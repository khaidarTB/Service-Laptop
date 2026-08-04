<x-app-layout>
    <x-slot name="header">Buat Tiket Servis Baru</x-slot>

    <div class="max-w-3xl mx-auto bg-white p-8 rounded-3xl border border-slate-200/80 shadow-xs">
        <form action="{{ route('admin.services.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="flex justify-between items-center p-4 rounded-2xl bg-blue-50 border border-blue-200">
                <span class="text-xs font-bold text-blue-900 uppercase tracking-wider">Nomor Tiket Otomatis</span>
                <span class="text-lg font-black text-blue-700">{{ $ticketNumber }}</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Customer Selection -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Pilih Customer *</label>
                    <select name="customer_id" required class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-sm font-medium bg-white">
                        <option value="">-- Pilih Customer --</option>
                        @foreach($customers as $c)
                            <option value="{{ $c->id }}">{{ $c->name }} (WA: {{ $c->whatsapp }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Technician Assignment -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tunjuk Teknisi Penanggung Jawab</label>
                    <select name="assigned_technician_id" class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-sm font-medium bg-white">
                        <option value="">-- Belum Ditunjuk --</option>
                        @foreach($technicians as $t)
                            <option value="{{ $t->id }}">{{ $t->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Laptop Info -->
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
                <a href="{{ route('admin.services.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold px-6 py-3 rounded-2xl text-sm transition">Batal</a>
            </div>
        </form>
    </div>
</x-app-layout>
