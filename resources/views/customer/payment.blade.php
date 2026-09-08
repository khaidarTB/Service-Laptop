<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Invoice {{ $service->transaction?->invoice_number }} - LaptopCare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ $clientKey }}"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen">

    <!-- Navbar -->
    <nav class="bg-white/90 backdrop-blur-md sticky top-0 z-40 border-b border-slate-200 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <a href="/" class="text-2xl text-blue-600 font-extrabold tracking-tight flex items-center gap-2">
                <i class="fas fa-laptop-medical"></i> LaptopCare
            </a>
            <div class="flex items-center gap-3">
                <a href="{{ route('customer.services.show', $service->id) }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold px-4 py-2.5 rounded-2xl text-xs transition">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali ke Tiket
                </a>
            </div>
        </div>
    </nav>

    <!-- Content Container -->
    <main class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8 space-y-8 my-6">

        {{-- Flash Messages --}}
        @if(session('error'))
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 text-xs sm:text-sm flex items-start gap-3 text-left">
                <i class="fas fa-circle-exclamation text-rose-600 text-base mt-0.5"></i>
                <div>
                    <h4 class="font-bold">Gagal!</h4>
                    <p class="mt-0.5">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs sm:text-sm flex items-start gap-3 text-left">
                <i class="fas fa-circle-check text-emerald-600 text-base mt-0.5"></i>
                <div>
                    <h4 class="font-bold">Berhasil!</h4>
                    <p class="mt-0.5">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Invoice Header -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs space-y-4">
            <div class="flex items-center justify-between flex-wrap gap-3">
                <div class="space-y-1">
                    <span class="text-xs font-bold text-blue-600 bg-blue-50 px-3 py-1 rounded-xl border border-blue-200">
                        <i class="fas fa-file-invoice mr-1"></i>INVOICE
                    </span>
                    <h1 class="text-xl sm:text-2xl font-black text-slate-900">{{ $service->transaction?->invoice_number ?? '-' }}</h1>
                </div>
                <div class="text-right space-y-1">
                    <span class="text-xs text-slate-400 font-bold uppercase tracking-wider block">Tiket Servis:</span>
                    <span class="text-sm font-black text-slate-900">{{ $service->ticket_number }}</span>
                </div>
            </div>
            <div class="border-t border-slate-100 pt-4 flex items-center justify-between flex-wrap gap-2">
                <div class="text-xs text-slate-500 space-y-0.5">
                    <p>{{ $service->laptop_brand }} {{ $service->laptop_type }}</p>
                    <p>Atas Nama: <b>{{ $service->customer?->name ?? '-' }}</b></p>
                </div>
                <div class="text-right">
                    <span class="text-[11px] text-slate-400 font-bold uppercase">Total Tagihan</span>
                    <p class="text-2xl font-black text-blue-600">Rp {{ number_format($service->total_cost, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Payment Status Badge -->
        @if($service->transaction)
            <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs space-y-4">
                <h3 class="font-extrabold text-slate-900 text-base border-b border-slate-100 pb-3 flex items-center gap-2">
                    <i class="fas fa-circle-info text-blue-600"></i> Status Pembayaran
                </h3>
                <div class="flex items-center gap-3 flex-wrap">
                    @if($service->transaction->payment_status === 'Lunas')
                        <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 text-xs font-extrabold px-4 py-2 rounded-xl border border-emerald-200">
                            <i class="fas fa-circle-check"></i> Lunas
                        </span>
                        <p class="text-xs text-slate-500">Pembayaran telah diterima. Terima kasih!</p>
                    @elseif($service->transaction->payment_status === 'DP')
                        <span class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-700 text-xs font-extrabold px-4 py-2 rounded-xl border border-amber-200">
                            <i class="fas fa-clock"></i> DP / Sebagian
                        </span>
                        <p class="text-xs text-slate-500">Pembayaran DP telah diterima. Silakan lunasi sisa tagihan.</p>
                    @else
                        <span class="inline-flex items-center gap-1.5 bg-rose-50 text-rose-700 text-xs font-extrabold px-4 py-2 rounded-xl border border-rose-200">
                            <i class="fas fa-hourglass-half"></i> Belum Bayar
                        </span>
                        <p class="text-xs text-slate-500">Silakan lakukan pembayaran untuk melanjutkan.</p>
                    @endif
                </div>
            </div>
        @endif

        <!-- Order Breakdown -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs space-y-4">
            <h3 class="font-extrabold text-slate-900 text-base border-b border-slate-100 pb-3 flex items-center gap-2">
                <i class="fas fa-receipt text-blue-600"></i> Rincian Biaya
            </h3>
            <div class="divide-y divide-slate-100 text-xs">
                <div class="py-2.5 flex justify-between font-bold text-slate-600">
                    <span>Jasa Servis (Teknisi):</span>
                    <span>Rp {{ number_format($service->service_fee, 0, ',', '.') }}</span>
                </div>
                @forelse($service->details as $detail)
                    <div class="py-2.5 flex justify-between text-slate-800 font-medium">
                        <span>{{ $detail->sparepart?->part_name ?? 'Part' }} ({{ $detail->quantity }}x)</span>
                        <span class="font-bold text-blue-600">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                    </div>
                @empty
                    <p class="text-slate-400 py-3 text-center">Tidak ada sparepart yang digunakan.</p>
                @endforelse
                <div class="py-3 flex justify-between items-center">
                    <span class="font-black text-sm text-slate-900">Total yang harus dibayar:</span>
                    <span class="font-black text-lg text-blue-600">Rp {{ number_format($service->total_cost, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        {{-- Only show payment button if not yet paid --}}
        @if($service->transaction && $service->transaction->payment_status !== 'Lunas')
            <!-- Pay Now Button (Midtrans Snap) -->
            <div class="bg-white p-8 sm:p-10 rounded-3xl border border-slate-200/80 shadow-xs text-center space-y-6">
                <div class="space-y-2">
                    <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-3xl flex items-center justify-center mx-auto text-2xl font-black shadow-lg shadow-blue-500/10 border border-blue-200">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <h2 class="text-xl font-black text-slate-900">Bayar Sekarang</h2>
                    <p class="text-xs sm:text-sm text-slate-500">Klik tombol di bawah untuk melakukan pembayaran secara online via Midtrans.</p>
                </div>

                <button id="pay-button" onclick="payWithSnap()" class="w-full max-w-sm mx-auto py-4 bg-blue-600 hover:bg-blue-700 text-white font-extrabold rounded-2xl text-sm transition shadow-xl shadow-blue-600/30 flex items-center justify-center gap-2">
                    <i class="fas fa-bolt"></i> Bayar Sekarang - Rp {{ number_format($service->total_cost, 0, ',', '.') }}
                </button>

                {{-- Fallback: Manual Payment Options --}}
                <div class="border-t border-slate-100 pt-6 space-y-4">
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Atau Bayar Manual</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-left">
                        {{-- QRIS --}}
                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 space-y-2">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 bg-slate-200 rounded-lg flex items-center justify-center text-slate-600">
                                    <i class="fas fa-qrcode text-sm"></i>
                                </div>
                                <span class="font-extrabold text-xs text-slate-900">QRIS</span>
                            </div>
                            <p class="text-[11px] text-slate-500 leading-relaxed">Minta QRIS ke kasir di workshop atau hubungi admin via WhatsApp untuk mendapatkan QR Code pembayaran.</p>
                        </div>
                        {{-- Transfer Bank --}}
                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 space-y-2">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 bg-slate-200 rounded-lg flex items-center justify-center text-slate-600">
                                    <i class="fas fa-building-columns text-sm"></i>
                                </div>
                                <span class="font-extrabold text-xs text-slate-900">Transfer Bank</span>
                            </div>
                            <p class="text-[11px] text-slate-500 leading-relaxed">Lakukan transfer sesuai nominal tagihan. Konfirmasi bukti transfer ke admin untuk diverifikasi.</p>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white p-8 sm:p-12 rounded-3xl border border-slate-200/80 shadow-xs text-center space-y-4">
                <div class="w-20 h-20 bg-emerald-50 text-emerald-600 rounded-3xl flex items-center justify-center mx-auto text-3xl border border-emerald-200">
                    <i class="fas fa-circle-check"></i>
                </div>
                <h2 class="text-xl font-black text-slate-900">Pembayaran Lunas</h2>
                <p class="text-sm text-slate-500 max-w-md mx-auto">Tagihan untuk tiket ini sudah terbayar. Anda tidak perlu melakukan pembayaran lagi.</p>
                <a href="{{ route('customer.services.show', $service->id) }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-extrabold px-6 py-3 rounded-2xl text-sm transition shadow-xl shadow-blue-600/30 mt-4">
                    <i class="fas fa-arrow-right"></i> Lihat Status Servis
                </a>
            </div>
        @endif

    </main>

    <footer class="py-8 text-center text-xs text-slate-400 border-t border-slate-200">
        &copy; {{ date('Y') }} LaptopCare Service Center. All rights reserved.
    </footer>

    {{-- Midtrans Snap Integration --}}
    @if($service->transaction && $service->transaction->payment_status !== 'Lunas')
    <script>
        function payWithSnap() {
            var payButton = document.getElementById('pay-button');
            payButton.disabled = true;
            payButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...';

            var params = {
                transaction_details: {
                    order_id: "{{ $service->transaction->invoice_number }}",
                    gross_amount: {{ $service->total_cost }},
                },
                customer_details: {
                    first_name: "{{ $service->customer?->name ?? 'Customer' }}",
                    email: "{{ $service->customer?->email ?? '' }}",
                    phone: "{{ $service->customer?->whatsapp ?? '' }}",
                },
                callbacks: {
                    finish: "{{ route('customer.services.show', $service->id) }}"
                }
            };

            try {
                window.snap.pay("{{ $service->transaction->invoice_number }}", {
                    onSuccess: function(result) {
                        window.location.href = "{{ route('customer.services.show', $service->id) }}";
                    },
                    onPending: function(result) {
                        window.location.href = "{{ route('customer.services.show', $service->id) }}";
                    },
                    onError: function(result) {
                        alert("Pembayaran gagal. Silakan coba lagi atau hubungi admin.");
                        payButton.disabled = false;
                        payButton.innerHTML = '<i class="fas fa-bolt"></i> Bayar Sekarang - Rp {{ number_format($service->total_cost, 0, ',', '.') }}';
                    },
                    onClose: function() {
                        payButton.disabled = false;
                        payButton.innerHTML = '<i class="fas fa-bolt"></i> Bayar Sekarang - Rp {{ number_format($service->total_cost, 0, ',', '.') }}';
                    }
                });
            } catch (e) {
                payButton.disabled = false;
                payButton.innerHTML = '<i class="fas fa-bolt"></i> Bayar Sekarang - Rp {{ number_format($service->total_cost, 0, ',', '.') }}';
            }
        }
    </script>
    @endif

</body>
</html>
