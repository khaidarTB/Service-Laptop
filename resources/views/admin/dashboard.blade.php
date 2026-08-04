<x-app-layout>
    <x-slot name="header">
        Dashboard Analytics & Overview
    </x-slot>

    <div class="space-y-8">
        <!-- Top Metrics Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5">
            <!-- Metric 1 -->
            <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-xs space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Servis Hari Ini</span>
                    <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                </div>
                <p class="text-3xl font-black text-slate-900">{{ number_format($totalServisHariIni) }}</p>
                <p class="text-xs text-slate-500 font-medium">Tiket masuk hari ini</p>
            </div>

            <!-- Metric 2 -->
            <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-xs space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Dalam Proses</span>
                    <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                        <i class="fas fa-spinner animate-spin"></i>
                    </div>
                </div>
                <p class="text-3xl font-black text-slate-900">{{ number_format($servisDalamProses) }}</p>
                <p class="text-xs text-amber-600 font-medium font-semibold">Memerlukan pengerjaan</p>
            </div>

            <!-- Metric 3 -->
            <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-xs space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Servis Selesai</span>
                    <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                        <i class="fas fa-circle-check"></i>
                    </div>
                </div>
                <p class="text-3xl font-black text-slate-900">{{ number_format($servisSelesai) }}</p>
                <p class="text-xs text-emerald-600 font-medium">Siap diambil customer</p>
            </div>

            <!-- Metric 4 -->
            <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-xs space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pendapatan Hari Ini</span>
                    <div class="w-9 h-9 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center font-bold">
                        <i class="fas fa-wallet"></i>
                    </div>
                </div>
                <p class="text-2xl font-black text-slate-900">Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}</p>
                <p class="text-xs text-slate-500 font-medium">Pembayaran Lunas</p>
            </div>

            <!-- Metric 5 -->
            <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-xs space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pendapatan Bulan Ini</span>
                    <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
                <p class="text-2xl font-black text-slate-900">Rp {{ number_format($pendapatanBulanan, 0, ',', '.') }}</p>
                <p class="text-xs text-indigo-600 font-medium font-semibold">Total Omset Bulan Ini</p>
            </div>
        </div>

        <!-- Charts Grid (Chart.js) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Monthly Services Chart -->
            <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <div>
                        <h3 class="font-bold text-slate-900 text-lg">Grafik Servis Bulanan</h3>
                        <p class="text-xs text-slate-400">Jumlah unit laptop masuk 6 bulan terakhir</p>
                    </div>
                    <span class="w-3 h-3 bg-blue-600 rounded-full"></span>
                </div>
                <div class="h-64">
                    <canvas id="servicesChart"></canvas>
                </div>
            </div>

            <!-- Monthly Revenue Chart -->
            <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <div>
                        <h3 class="font-bold text-slate-900 text-lg">Grafik Pendapatan Bulanan</h3>
                        <p class="text-xs text-slate-400">Omset pendapatan 6 bulan terakhir (Rp)</p>
                    </div>
                    <span class="w-3 h-3 bg-indigo-600 rounded-full"></span>
                </div>
                <div class="h-64">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Lower Section: Low Stock & Productivity -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Sparepart Hampir Habis (Low Stock) -->
            <div class="lg:col-span-6 bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-triangle-exclamation text-amber-500 text-lg"></i>
                        <h3 class="font-bold text-slate-900 text-lg">Sparepart Hampir Habis</h3>
                    </div>
                    <a href="{{ route('admin.spareparts.index', ['low_stock' => 1]) }}" class="text-xs font-bold text-blue-600 hover:underline">Kelola Sparepart</a>
                </div>

                @if($sparepartHampirHabis->isEmpty())
                    <p class="text-xs text-slate-400 text-center py-6">Semua stok sparepart dalam kondisi aman.</p>
                @else
                    <div class="divide-y divide-slate-100 max-h-60 overflow-y-auto">
                        @foreach($sparepartHampirHabis as $part)
                            <div class="py-3 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $part->image ?? 'https://images.unsplash.com/photo-1597872200969-2b65d56bd16b?w=100' }}" class="w-10 h-10 rounded-xl object-cover border border-slate-200">
                                    <div>
                                        <p class="font-bold text-sm text-slate-900">{{ $part->part_name }}</p>
                                        <p class="text-xs text-slate-400">Harga: Rp {{ number_format($part->selling_price, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                                <span class="bg-rose-100 text-rose-800 text-xs font-bold px-3 py-1 rounded-xl border border-rose-200">
                                    Stok: {{ $part->stock }} (Min: {{ $part->min_stock }})
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Teknisi Paling Produktif -->
            <div class="lg:col-span-6 bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-trophy text-amber-500 text-lg"></i>
                        <h3 class="font-bold text-slate-900 text-lg">Teknisi Paling Produktif</h3>
                    </div>
                    <span class="text-xs text-slate-400">Top Performers</span>
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse($teknisiProduktif as $index => $tek)
                        <div class="py-3 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-xl font-black text-xs flex items-center justify-center {{ $index === 0 ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-600' }}">
                                    #{{ $index + 1 }}
                                </div>
                                <div>
                                    <p class="font-bold text-sm text-slate-900">{{ $tek->name }}</p>
                                    <p class="text-xs text-slate-400">{{ $tek->email }}</p>
                                </div>
                            </div>
                            <span class="bg-blue-50 text-blue-700 text-xs font-bold px-3 py-1 rounded-xl border border-blue-200">
                                {{ $tek->assigned_services_count }} Unit Selesai
                            </span>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 text-center py-6">Belum ada data pengerjaan teknisi.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const months = @json($months);
                const serviceData = @json($serviceChartData);
                const revenueData = @json($revenueChartData);

                // Services Chart
                new Chart(document.getElementById('servicesChart'), {
                    type: 'bar',
                    data: {
                        labels: months,
                        datasets: [{
                            label: 'Jumlah Servis',
                            data: serviceData,
                            backgroundColor: '#2563eb',
                            borderRadius: 8,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: { y: { beginAtZero: true } }
                    }
                });

                // Revenue Chart
                new Chart(document.getElementById('revenueChart'), {
                    type: 'line',
                    data: {
                        labels: months,
                        datasets: [{
                            label: 'Pendapatan (Rp)',
                            data: revenueData,
                            borderColor: '#4f46e5',
                            backgroundColor: 'rgba(79, 70, 229, 0.1)',
                            fill: true,
                            tension: 0.4,
                            pointRadius: 5
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: { y: { beginAtZero: true } }
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>
