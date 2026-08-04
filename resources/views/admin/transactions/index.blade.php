<x-app-layout>
    <x-slot name="header">Data Transaksi & Invoice Pembayaran</x-slot>

    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.transactions.index') }}" class="px-3.5 py-2 rounded-2xl text-xs font-bold transition {{ !$status ? 'bg-blue-600 text-white' : 'bg-white text-slate-600 border border-slate-200' }}">Semua Status</a>
                <a href="{{ route('admin.transactions.index', ['payment_status' => 'Lunas']) }}" class="px-3 py-1.5 rounded-2xl text-xs font-bold transition {{ $status === 'Lunas' ? 'bg-emerald-600 text-white' : 'bg-white text-emerald-700 border border-emerald-200' }}">Lunas</a>
                <a href="{{ route('admin.transactions.index', ['payment_status' => 'DP']) }}" class="px-3 py-1.5 rounded-2xl text-xs font-bold transition {{ $status === 'DP' ? 'bg-amber-600 text-white' : 'bg-white text-amber-700 border border-amber-200' }}">DP / Uang Muka</a>
                <a href="{{ route('admin.transactions.index', ['payment_status' => 'Belum Bayar']) }}" class="px-3 py-1.5 rounded-2xl text-xs font-bold transition {{ $status === 'Belum Bayar' ? 'bg-rose-600 text-white' : 'bg-white text-rose-700 border border-rose-200' }}">Belum Bayar</a>
            </div>

            <a href="{{ route('admin.transactions.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-2xl font-bold text-sm transition shadow-lg shadow-blue-600/30 flex items-center justify-center gap-2">
                <i class="fas fa-receipt"></i> + Buat Invoice Transaksi
            </a>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-700">
                    <thead class="bg-slate-50 border-b border-slate-100 text-xs font-bold uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="py-4 px-6">No Invoice & Tiket</th>
                            <th class="py-4 px-6">Pelanggan</th>
                            <th class="py-4 px-6">Metode Pembayaran</th>
                            <th class="py-4 px-6 text-center">Status Bayar</th>
                            <th class="py-4 px-6">Jumlah Bayar</th>
                            <th class="py-4 px-6 text-right">Faktur</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @forelse($transactions as $t)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-4 px-6">
                                    <div>
                                        <a href="{{ route('admin.transactions.invoice', $t->id) }}" target="_blank" class="font-extrabold text-blue-600 hover:underline">
                                            {{ $t->invoice_number }}
                                        </a>
                                        <p class="text-xs text-slate-400">Tiket: {{ $t->service ? $t->service->ticket_number : '-' }}</p>
                                    </div>
                                </td>
                                <td class="py-4 px-6 font-bold text-slate-900">
                                    {{ $t->service && $t->service->customer ? $t->service->customer->name : '-' }}
                                </td>
                                <td class="py-4 px-6">
                                    <span class="bg-slate-100 text-slate-700 px-3 py-1 rounded-xl text-xs font-bold border border-slate-200">
                                        {{ $t->payment_method }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <span class="px-3 py-1 rounded-xl text-xs font-extrabold border {{ $t->payment_status === 'Lunas' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : ($t->payment_status === 'DP' ? 'bg-amber-50 text-amber-700 border-amber-200' : 'bg-rose-50 text-rose-700 border-rose-200') }}">
                                        {{ $t->payment_status }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 font-black text-slate-900">
                                    Rp {{ number_format($t->amount_paid, 0, ',', '.') }}
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <a href="{{ route('admin.transactions.invoice', $t->id) }}" target="_blank" class="p-2 text-slate-500 hover:text-blue-600 transition font-bold text-xs flex items-center justify-end gap-1">
                                        <i class="fas fa-print"></i> Cetak
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-slate-400">Belum ada transaksi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
