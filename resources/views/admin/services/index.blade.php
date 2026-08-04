<x-app-layout>
    <x-slot name="header">Data Tiket Servis Laptop</x-slot>

    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-2 overflow-x-auto pb-2 sm:pb-0">
                <a href="{{ route('admin.services.index') }}" class="px-3.5 py-2 rounded-2xl text-xs font-bold whitespace-nowrap transition {{ !$status ? 'bg-blue-600 text-white' : 'bg-white text-slate-600 border border-slate-200' }}">Semua Status</a>
                @foreach($statuses as $st)
                    @php
                        $badgeStyle = match($st) {
                            'antrean' => 'bg-slate-100 text-slate-700',
                            'pemeriksaan' => 'bg-amber-50 text-amber-700 border-amber-200',
                            'menunggu_persetujuan' => 'bg-orange-50 text-orange-700 border-orange-200',
                            'perbaikan' => 'bg-blue-50 text-blue-700 border-blue-200',
                            'selesai' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            'diambil' => 'bg-purple-50 text-purple-700 border-purple-200',
                            'batal' => 'bg-rose-50 text-rose-700 border-rose-200',
                            default => 'bg-slate-100 text-slate-700'
                        };
                    @endphp
                    <a href="{{ route('admin.services.index', ['status' => $st]) }}"
                       class="px-3 py-1.5 rounded-2xl text-xs font-bold border capitalize whitespace-nowrap transition {{ $status === $st ? 'bg-slate-900 text-white border-slate-900' : $badgeStyle }}">
                        {{ str_replace('_', ' ', $st) }}
                    </a>
                @endforeach
            </div>

            <a href="{{ route('admin.services.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-2xl font-bold text-sm transition shadow-lg shadow-blue-600/30 flex items-center justify-center gap-2 whitespace-nowrap">
                <i class="fas fa-plus"></i> + Buat Tiket Servis
            </a>
        </div>

        <!-- Search Bar -->
        <form action="{{ route('admin.services.index') }}" method="GET" class="max-w-md">
            <div class="relative">
                <i class="fas fa-search absolute left-3.5 top-3 text-slate-400"></i>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari No. Tiket, Merek, Customer, WhatsApp..."
                       class="w-full pl-10 pr-4 py-2.5 rounded-2xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-sm font-medium bg-white">
            </div>
        </form>

        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-700">
                    <thead class="bg-slate-50 border-b border-slate-100 text-xs font-bold uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="py-4 px-6">No Tiket & Laptop</th>
                            <th class="py-4 px-6">Pelanggan</th>
                            <th class="py-4 px-6">Teknisi</th>
                            <th class="py-4 px-6">Status Perbaikan</th>
                            <th class="py-4 px-6">Est. Biaya Total</th>
                            <th class="py-4 px-6 text-right">Detail</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @forelse($services as $s)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-4 px-6">
                                    <div class="space-y-0.5">
                                        <a href="{{ route('admin.services.show', $s->id) }}" class="font-extrabold text-blue-600 hover:underline text-sm">
                                            {{ $s->ticket_number }}
                                        </a>
                                        <p class="font-bold text-slate-900 text-sm">{{ $s->laptop_brand }} {{ $s->laptop_type }}</p>
                                        <p class="text-xs text-slate-400">Masuk: {{ $s->date_received ? $s->date_received->format('d M Y') : '-' }}</p>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <div>
                                        <p class="font-bold text-slate-900">{{ $s->customer ? $s->customer->name : 'N/A' }}</p>
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $s->customer ? $s->customer->whatsapp : '') }}" target="_blank" class="text-xs text-blue-600 font-semibold hover:underline">
                                            <i class="fab fa-whatsapp text-emerald-500"></i> {{ $s->customer ? $s->customer->whatsapp : '-' }}
                                        </a>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    @if($s->technician)
                                        <span class="bg-blue-50 text-blue-700 text-xs font-bold px-2.5 py-1 rounded-xl border border-blue-100">
                                            <i class="fas fa-user-gear mr-1"></i> {{ $s->technician->name }}
                                        </span>
                                    @else
                                        <span class="text-xs text-slate-400 italic">Belum Ditunjuk</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6">
                                    <span class="{{ $s->status_badge_class }} px-3 py-1 rounded-xl text-xs font-extrabold shadow-2xs border">
                                        {{ $s->status_label }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 font-black text-slate-900">
                                    Rp {{ number_format($s->total_cost, 0, ',', '.') }}
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <a href="{{ route('admin.services.show', $s->id) }}" class="inline-flex items-center gap-1 bg-slate-100 hover:bg-blue-600 hover:text-white px-3.5 py-2 rounded-xl text-xs font-bold transition">
                                        Lihat Tiket <i class="fas fa-chevron-right text-[10px]"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-slate-400">Belum ada tiket servis.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $services->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
