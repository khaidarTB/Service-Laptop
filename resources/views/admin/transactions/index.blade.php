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

            <button @click="open = true" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-2xl font-bold text-sm transition shadow-lg shadow-blue-600/30 flex items-center justify-center gap-2">
                <i class="fas fa-receipt"></i> + Buat Invoice Transaksi
            </button>
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
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('admin.transactions.invoice', $t->id) }}" target="_blank" class="p-2 text-slate-500 hover:text-blue-600 transition font-bold text-xs flex items-center gap-1">
                                            <i class="fas fa-print"></i> Cetak
                                        </a>
                                        <button type="button" onclick="confirmDelete({{ $t->id }}, '{{ $t->invoice_number }}')" class="p-2 text-slate-500 hover:text-rose-600 transition font-bold text-xs flex items-center gap-1">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
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

    {{-- Create Modal --}}
    <div x-data="{ open: false }" x-cloak>
        <div x-show="open" x-transition.opacity class="fixed inset-0 z-50">
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="open = false"></div>
            <div class="fixed inset-0 flex items-center justify-center p-4">
                <div class="bg-white rounded-3xl shadow-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto p-6" @click.stop>
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-black text-slate-900">Buat Invoice Transaksi Baru</h2>
                        <button @click="open = false" class="p-2 text-slate-400 hover:text-slate-600 transition rounded-xl hover:bg-slate-100">
                            <i class="fas fa-xmark text-lg"></i>
                        </button>
                    </div>

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
                            <button type="button" @click="open = false" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold px-6 py-3 rounded-2xl text-sm transition">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function confirmDelete(id, invoice) {
            Swal.fire({
                title: 'Hapus Transaksi?',
                html: `Apakah Anda yakin ingin menghapus transaksi <b>${invoice}</b>?`,
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
                    form.action = `{{ url('admin/transactions') }}/${id}`;
                    form.innerHTML = `
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="DELETE">
                    `;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
    @endpush
</x-app-layout>
