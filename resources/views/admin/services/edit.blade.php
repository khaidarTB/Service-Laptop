<x-app-layout>
    <x-slot name="header">Edit Tiket Servis: {{ $service->ticket_number }}</x-slot>

    <div class="max-w-3xl mx-auto bg-white p-8 rounded-3xl border border-slate-200/80 shadow-xs">
        <form action="{{ route('admin.services.update', $service->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Pelanggan *</label>
                    <select name="customer_id" required class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm font-medium bg-white">
                        @foreach($customers as $c)
                            <option value="{{ $c->id }}" {{ $service->customer_id === $c->id ? 'selected' : '' }}>{{ $c->name }} ({{ $c->whatsapp }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Teknisi Penanggung Jawab</label>
                    <select name="assigned_technician_id" class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm font-medium bg-white">
                        <option value="">-- Belum Ditunjuk --</option>
                        @foreach($technicians as $t)
                            <option value="{{ $t->id }}" {{ $service->assigned_technician_id === $t->id ? 'selected' : '' }}>{{ $t->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Merek *</label>
                    <input type="text" name="laptop_brand" value="{{ old('laptop_brand', $service->laptop_brand) }}" required class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm font-medium">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tipe / Seri *</label>
                    <input type="text" name="laptop_type" value="{{ old('laptop_type', $service->laptop_type) }}" required class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm font-medium">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Serial Number</label>
                    <input type="text" name="serial_number" value="{{ old('serial_number', $service->serial_number) }}" class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm font-medium">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kelengkapan</label>
                    <input type="text" name="equipment" value="{{ old('equipment', $service->equipment) }}" class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm font-medium">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Keluhan Kerusakan *</label>
                <textarea name="complaint" required rows="3" class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm font-medium">{{ old('complaint', $service->complaint) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Diagnosa Perbaikan</label>
                <textarea name="diagnosis" rows="3" class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm font-medium">{{ old('diagnosis', $service->diagnosis) }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Biaya Jasa Servis (Rp)</label>
                    <input type="number" name="service_fee" value="{{ old('service_fee', $service->service_fee) }}" class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm font-medium">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Estimasi Biaya Total (Rp)</label>
                    <input type="number" name="estimated_cost" value="{{ old('estimated_cost', $service->estimated_cost) }}" class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm font-medium">
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex gap-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-3 rounded-2xl text-sm transition">Perbarui Tiket</button>
                <a href="{{ route('admin.services.show', $service->id) }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold px-6 py-3 rounded-2xl text-sm transition">Batal</a>
            </div>
        </form>
    </div>
</x-app-layout>
