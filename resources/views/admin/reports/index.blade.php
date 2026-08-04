<x-app-layout>
    <x-slot name="header">Rekapitulasi Laporan & Analytics</x-slot>

    <div class="space-y-8">
        <!-- Date Filter & Export Header Bar -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <form action="{{ route('admin.reports.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <span class="text-xs font-bold text-slate-500 uppercase">Dari:</span>
                    <input type="date" name="start_date" value="{{ $startDate }}" class="px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold bg-white">
                </div>
                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <span class="text-xs font-bold text-slate-500 uppercase">Sampai:</span>
                    <input type="date" name="end_date" value="{{ $endDate }}" class="px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold bg-white">
                </div>
                <button type="submit" class="w-full sm:w-auto bg-slate-900 hover:bg-slate-800 text-white font-bold px-4 py-2 rounded-xl text-xs transition">
                    Filter Periode
                </button>
            </form>

            <div class="flex items-center gap-2">
                <a href="{{ route('admin.reports.pdf', ['start_date' => $startDate, 'end_date' => $endDate]) }}" target="_blank" class="bg-rose-600 hover:bg-rose-700 text-white font-bold px-4 py-2.5 rounded-xl text-xs transition shadow-md shadow-rose-600/20 flex items-center gap-1.5">
                    <i class="fas fa-file-pdf"></i> Cetak PDF
                </a>
                <a href="{{ route('admin.reports.excel', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-4 py-2.5 rounded-xl text-xs transition shadow-md shadow-emerald-600/20 flex items-center gap-1.5">
                    <i class="fas fa-file-excel"></i> Export Excel (CSV)
                </a>
            </div>
        </div>

        <!-- Summary Statistics Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs space-y-2">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Pendapatan (Lunas)</span>
                <p class="text-3xl font-black text-emerald-600">Rp {{ number_format($totalIncome, 0, ',', '.') }}</p>
                <p class="text-xs text-slate-500">Periode {{ Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs space-y-2">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Servis Masuk</span>
                <p class="text-3xl font-black text-blue-600">{{ number_format($totalServices) }} Unit</p>
                <p class="text-xs text-slate-500">Dalam rentang periode terpilih</p>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs space-y-2">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Servis Selesai</span>
                <p class="text-3xl font-black text-indigo-600">{{ number_format($completedServices) }} Unit</p>
                <p class="text-xs text-slate-500">Telah selesai dikerjakan</p>
            </div>
        </div>

        <!-- Analytics Breakdowns Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Merek Laptop Terbanyak Servis -->
            <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs space-y-4">
                <h3 class="font-bold text-slate-900 text-base border-b border-slate-100 pb-3 flex items-center gap-2">
                    <i class="fas fa-laptop text-blue-600"></i> Merek Laptop Paling Sering Diservis
                </h3>
                <div class="space-y-3">
                    @forelse($topBrands as $b)
                        <div class="flex justify-between items-center text-sm font-semibold">
                            <span class="text-slate-800">{{ $b->laptop_brand }}</span>
                            <span class="bg-blue-50 text-blue-700 px-3 py-1 rounded-xl text-xs font-black border border-blue-200">
                                {{ $b->total }} Unit
                            </span>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 py-4 text-center">Belum ada data.</p>
                    @endforelse
                </div>
            </div>

            <!-- Sparepart Paling Banyak Terpakai -->
            <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs space-y-4">
                <h3 class="font-bold text-slate-900 text-base border-b border-slate-100 pb-3 flex items-center gap-2">
                    <i class="fas fa-boxes-packing text-blue-600"></i> Sparepart Terlaris / Paling Banyak Terpakai
                </h3>
                <div class="space-y-3">
                    @forelse($topSpareparts as $sp)
                        <div class="flex justify-between items-center text-sm font-semibold">
                            <span class="text-slate-800">{{ $sp->sparepart ? $sp->sparepart->part_name : 'Part' }}</span>
                            <div class="flex items-center gap-2">
                                <span class="text-xs text-slate-500">Total: Rp {{ number_format($sp->total_amount, 0, ',', '.') }}</span>
                                <span class="bg-emerald-50 text-emerald-700 px-3 py-1 rounded-xl text-xs font-black border border-emerald-200">
                                    {{ $sp->total_qty }} Qty
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 py-4 text-center">Belum ada data sparepart terpakai.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="p-6 border-b border-slate-100 font-bold text-slate-900 text-base">
                Rincian Transaksi Pembayaran
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-700">
                    <thead class="bg-slate-50 border-b border-slate-100 text-xs font-bold uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="py-4 px-6">No Invoice</th>
                            <th class="py-4 px-6">Pelanggan</th>
                            <th class="py-4 px-6">Metode</th>
                            <th class="py-4 px-6">Status</th>
                            <th class="py-4 px-6">Tanggal</th>
                            <th class="py-4 px-6 text-right">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @forelse($transactions as $t)
                            <tr>
                                <td class="py-4 px-6 font-bold text-blue-600">{{ $t->invoice_number }}</td>
                                <td class="py-4 px-6 font-bold text-slate-900">{{ $t->service && $t->service->customer ? $t->service->customer->name : '-' }}</td>
                                <td class="py-4 px-6">{{ $t->payment_method }}</td>
                                <td class="py-4 px-6">
                                    <span class="px-2.5 py-0.5 rounded-lg text-xs font-bold {{ $t->payment_status === 'Lunas' ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                                        {{ $t->payment_status }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-xs text-slate-500">{{ $t->transaction_date ? $t->transaction_date->format('d/m/Y H:i') : '-' }}</td>
                                <td class="py-4 px-6 text-right font-black text-slate-900">Rp {{ number_format($t->amount_paid, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-slate-400">Tidak ada transaksi dalam periode ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
