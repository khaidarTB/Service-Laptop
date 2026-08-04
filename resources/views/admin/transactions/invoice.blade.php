<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faktur Invoice - {{ $transaction->invoice_number }}</title>
    @vite(['resources/css/app.css'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; }
            .invoice-card { border: none !important; shadow: none !important; }
        }
    </style>
</head>
<body class="bg-slate-100 p-4 sm:p-8 min-h-screen">

    <div class="max-w-3xl mx-auto space-y-4">
        <!-- Print Top Control -->
        <div class="no-print flex justify-between items-center bg-slate-900 text-white p-4 rounded-2xl shadow-lg">
            <span class="text-xs font-bold"><i class="fas fa-print mr-2"></i> Mode Cetak Faktur Tagihan</span>
            <div class="flex gap-2">
                <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-4 py-2 rounded-xl text-xs transition">
                    <i class="fas fa-print"></i> Cetak Invoice
                </button>
                <a href="{{ route('admin.transactions.index') }}" class="bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold px-4 py-2 rounded-xl text-xs transition">
                    Kembali
                </a>
            </div>
        </div>

        <!-- Printable Invoice Sheet -->
        <div class="invoice-card bg-white p-8 sm:p-12 rounded-3xl border border-slate-200/80 shadow-xl space-y-8">
            <!-- Invoice Header -->
            <div class="flex justify-between items-start border-b border-slate-200 pb-6">
                <div>
                    <h1 class="text-2xl font-black text-blue-600 tracking-tight flex items-center gap-2">
                        <i class="fas fa-laptop-medical"></i> LaptopCare
                    </h1>
                    <p class="text-xs text-slate-500 mt-1">Pusat Service & Sparepart Laptop Profesional</p>
                    <p class="text-xs text-slate-400">Jl. Raya Tekno No. 123 • WA: 0812-3456-7890</p>
                </div>
                <div class="text-right">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest block">INVOICE TAGIHAN</span>
                    <h2 class="text-xl font-extrabold text-slate-900 mt-0.5">{{ $transaction->invoice_number }}</h2>
                    <p class="text-xs text-slate-500 mt-1">Tanggal: {{ $transaction->transaction_date ? $transaction->transaction_date->format('d/m/Y H:i') : date('d/m/Y') }}</p>
                    <span class="inline-block mt-2 px-3 py-1 rounded-xl text-xs font-black uppercase border {{ $transaction->payment_status === 'Lunas' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-rose-50 text-rose-700 border-rose-200' }}">
                        STATUS: {{ $transaction->payment_status }}
                    </span>
                </div>
            </div>

            <!-- Customer & Service Info Grid -->
            <div class="grid grid-cols-2 gap-6 text-xs border-b border-slate-200 pb-6">
                <div>
                    <span class="font-bold text-slate-400 uppercase tracking-wider block mb-1">Kepada Yth:</span>
                    <p class="font-extrabold text-sm text-slate-900">{{ $transaction->service && $transaction->service->customer ? $transaction->service->customer->name : '-' }}</p>
                    <p class="text-slate-600 mt-0.5">WhatsApp: {{ $transaction->service && $transaction->service->customer ? $transaction->service->customer->whatsapp : '-' }}</p>
                    <p class="text-slate-500 mt-0.5 max-w-xs">{{ $transaction->service && $transaction->service->customer ? $transaction->service->customer->address : '-' }}</p>
                </div>

                <div>
                    <span class="font-bold text-slate-400 uppercase tracking-wider block mb-1">Detail Unit Laptop:</span>
                    <p class="font-extrabold text-sm text-slate-900">{{ $transaction->service ? $transaction->service->laptop_brand : '' }} {{ $transaction->service ? $transaction->service->laptop_type : '' }}</p>
                    <p class="text-slate-600 mt-0.5">No Tiket: <b>{{ $transaction->service ? $transaction->service->ticket_number : '-' }}</b></p>
                    <p class="text-slate-500 mt-0.5">Teknisi: {{ $transaction->service && $transaction->service->technician ? $transaction->service->technician->name : '-' }}</p>
                </div>
            </div>

            <!-- Itemized Table -->
            <div class="space-y-4">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 border-y border-slate-200 font-bold uppercase text-slate-600">
                        <tr>
                            <th class="py-3 px-4">Deskripsi Layanan / Part</th>
                            <th class="py-3 px-4 text-center">Harga</th>
                            <th class="py-3 px-4 text-center">Qty</th>
                            <th class="py-3 px-4 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-800">
                        <!-- Jasa Servis -->
                        <tr>
                            <td class="py-3 px-4">
                                <span class="font-bold">Biaya Jasa Servis Laptop</span>
                                <span class="block text-[10px] text-slate-400">Pemeriksaan, diagnosa & pengerjaan perbaikan</span>
                            </td>
                            <td class="py-3 px-4 text-center">Rp {{ number_format($transaction->service ? $transaction->service->service_fee : 0, 0, ',', '.') }}</td>
                            <td class="py-3 px-4 text-center">1x</td>
                            <td class="py-3 px-4 text-right font-bold">Rp {{ number_format($transaction->service ? $transaction->service->service_fee : 0, 0, ',', '.') }}</td>
                        </tr>

                        <!-- Spareparts -->
                        @if($transaction->service && $transaction->service->details)
                            @foreach($transaction->service->details as $detail)
                                <tr>
                                    <td class="py-3 px-4">
                                        <span class="font-bold">{{ $detail->sparepart ? $detail->sparepart->part_name : 'Sparepart' }}</span>
                                        <span class="block text-[10px] text-slate-400">Komponen pengganti original</span>
                                    </td>
                                    <td class="py-3 px-4 text-center">Rp {{ number_format($detail->price_at_time, 0, ',', '.') }}</td>
                                    <td class="py-3 px-4 text-center">{{ $detail->quantity }}x</td>
                                    <td class="py-3 px-4 text-right font-bold">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- Invoice Totals & Payment Method -->
            <div class="pt-4 border-t border-slate-200 grid grid-cols-2 gap-6 items-center">
                <div class="text-xs space-y-1">
                    <p class="font-bold text-slate-700">Metode Bayar: <span class="text-blue-600">{{ $transaction->payment_method }}</span></p>
                    <p class="text-slate-400 text-[11px]">Terima kasih telah mempercayakan perbaikan laptop Anda kepada LaptopCare!</p>
                </div>

                <div class="space-y-2 text-right">
                    <div class="flex justify-between text-xs text-slate-600 font-medium">
                        <span>Total Biaya:</span>
                        <span>Rp {{ number_format($transaction->service ? $transaction->service->total_cost : $transaction->amount_paid, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-base font-black text-slate-900 border-t border-slate-200 pt-2">
                        <span>Jumlah Dibayar:</span>
                        <span class="text-blue-600">Rp {{ number_format($transaction->amount_paid, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Footer Signatures -->
            <div class="pt-12 border-t border-slate-200 grid grid-cols-2 text-center text-xs text-slate-500">
                <div>
                    <p>Pelanggan,</p>
                    <div class="h-16"></div>
                    <p class="font-bold text-slate-800">({{ $transaction->service && $transaction->service->customer ? $transaction->service->customer->name : 'Pelanggan' }})</p>
                </div>
                <div>
                    <p>Hormat Kami (Kasir / Admin),</p>
                    <div class="h-16"></div>
                    <p class="font-bold text-slate-800">( LaptopCare Team )</p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
