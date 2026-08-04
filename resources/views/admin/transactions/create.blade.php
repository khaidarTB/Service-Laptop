<x-app-layout>
    <x-slot name="header">Buat Invoice Transaksi Baru</x-slot>

    <div class="max-w-2xl mx-auto bg-white p-8 rounded-3xl border border-slate-200/80 shadow-xs">
        <form action="{{ route('admin.transactions.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="flex justify-between items-center p-4 rounded-2xl bg-blue-50 border border-blue-200">
                <span class="text-xs font-bold text-blue-900 uppercase tracking-wider">Nomor Invoice Otomatis</span>
                <span class="text-lg font-black text-blue-700">{{ $invoiceNumber }}</span>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Pilih Tiket Servis *</label>
                    <select name="service_id" required class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-sm font-medium bg-white">
                        <option value="">-- Pilih Tiket Servis Belum Memiliki Invoice --</option>
                        @foreach($servicesWithoutTransaction as $s)
                            <option value="{{ $s->id }}">
                                {{ $s->ticket_number }} - {{ $s->laptop_brand }} {{ $s->laptop_type }} ({{ $s->customer ? $s->customer->name : '-' }}) - Total: Rp {{ number_format($s->total_cost, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Jumlah Pembayaran (Rp) *</label>
                    <input type="number" name="amount_paid" required placeholder="150000" class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm font-medium">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Metode Pembayaran *</label>
                        <select name="payment_method" required class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm font-medium bg-white">
                            <option value="Tunai">Tunai / Cash</option>
                            <option value="Transfer">Transfer Bank</option>
                            <option value="QRIS">QRIS / E-Wallet</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Status Pembayaran *</label>
                        <select name="payment_status" required class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm font-medium bg-white">
                            <option value="Lunas">Lunas</option>
                            <option value="DP">DP / Uang Muka</option>
                            <option value="Belum Bayar">Belum Bayar</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex gap-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-3 rounded-2xl text-sm transition">Simpan & Cetak Invoice</button>
                <a href="{{ route('admin.transactions.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold px-6 py-3 rounded-2xl text-sm transition">Batal</a>
            </div>
        </form>
    </div>
</x-app-layout>
