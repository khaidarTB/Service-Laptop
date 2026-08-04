<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pendapatan {{ $startDate }} s/d {{ $endDate }}</title>
    @vite(['resources/css/app.css'])
    <style>
        @media print {
            body { background: white !important; padding: 0 !important; }
        }
    </style>
</head>
<body class="bg-white p-8 text-slate-800 font-sans text-xs">

    <div class="space-y-6">
        <div class="flex justify-between items-center border-b-2 border-slate-900 pb-4">
            <div>
                <h1 class="text-xl font-black text-slate-900 uppercase">Laporan Keuangan & Pendapatan Servis</h1>
                <p class="text-slate-500 mt-0.5">LaptopCare - Sistem Informasi Manajemen Servis Laptop</p>
            </div>
            <div class="text-right">
                <p class="font-bold">Periode Laporan:</p>
                <p class="text-slate-600 font-semibold">{{ Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</p>
            </div>
        </div>

        <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 flex justify-between items-center">
            <span class="font-bold text-slate-700 text-sm">TOTAL PENDAPATAN LUNAS:</span>
            <span class="text-lg font-black text-blue-600">Rp {{ number_format($totalIncome, 0, ',', '.') }}</span>
        </div>

        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-900 text-white font-bold uppercase text-[10px]">
                    <th class="py-2.5 px-3 border border-slate-800">No Invoice</th>
                    <th class="py-2.5 px-3 border border-slate-800">No Tiket</th>
                    <th class="py-2.5 px-3 border border-slate-800">Pelanggan</th>
                    <th class="py-2.5 px-3 border border-slate-800">Metode Bayar</th>
                    <th class="py-2.5 px-3 border border-slate-800">Status</th>
                    <th class="py-2.5 px-3 border border-slate-800">Tanggal</th>
                    <th class="py-2.5 px-3 border border-slate-800 text-right">Jumlah Dibayar</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 font-medium">
                @foreach($transactions as $t)
                    <tr>
                        <td class="py-2 px-3 border border-slate-200 font-bold">{{ $t->invoice_number }}</td>
                        <td class="py-2 px-3 border border-slate-200">{{ $t->service ? $t->service->ticket_number : '-' }}</td>
                        <td class="py-2 px-3 border border-slate-200 font-bold">{{ $t->service && $t->service->customer ? $t->service->customer->name : '-' }}</td>
                        <td class="py-2 px-3 border border-slate-200">{{ $t->payment_method }}</td>
                        <td class="py-2 px-3 border border-slate-200 font-bold">{{ $t->payment_status }}</td>
                        <td class="py-2 px-3 border border-slate-200">{{ $t->transaction_date ? $t->transaction_date->format('d/m/Y H:i') : '-' }}</td>
                        <td class="py-2 px-3 border border-slate-200 text-right font-black">Rp {{ number_format($t->amount_paid, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="pt-8 flex justify-end text-center">
            <div class="w-48">
                <p>Dicetak Pada: {{ date('d/m/Y H:i') }}</p>
                <div class="h-16"></div>
                <p class="font-bold border-t border-slate-400 pt-1">Admin LaptopCare</p>
            </div>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
