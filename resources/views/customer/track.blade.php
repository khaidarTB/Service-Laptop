<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lacak Status Tiket Servis Laptop - LaptopCare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-b from-blue-50/60 to-slate-100 min-h-screen flex flex-col justify-between antialiased">

    <!-- Header Navigation -->
    <header class="bg-white/80 backdrop-blur-md border-b border-slate-200 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <a href="/" class="text-2xl text-blue-600 font-extrabold tracking-tight flex items-center gap-2">
                <i class="fas fa-laptop-medical"></i> LaptopCare
            </a>
            <a href="/" class="text-xs font-bold text-slate-600 hover:text-blue-600 bg-slate-100 px-4 py-2 rounded-xl transition">
                <i class="fas fa-arrow-left mr-1"></i> Kembali ke Beranda
            </a>
        </div>
    </header>

    <!-- Main Search Card Container -->
    <main class="max-w-xl w-full mx-auto p-4 sm:p-6 my-auto">
        <div class="bg-white p-8 sm:p-12 rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-100 space-y-8 text-center">
            <div class="space-y-2">
                <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-3xl flex items-center justify-center mx-auto text-2xl font-black shadow-lg shadow-blue-500/10">
                    <i class="fas fa-magnifying-glass"></i>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Lacak Tiket Perbaikan</h1>
                <p class="text-xs sm:text-sm text-slate-500">Masukkan Nomor Tiket Servis Anda (Contoh: <b>SRV-20260804-001</b>) atau Nomor WhatsApp terdaftar.</p>
            </div>

            @if(session('error'))
                <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 text-xs sm:text-sm flex items-start gap-3 text-left">
                    <i class="fas fa-circle-exclamation text-rose-600 text-base mt-0.5"></i>
                    <div>
                        <h4 class="font-bold">Tidak Ditemukan!</h4>
                        <p class="mt-0.5">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <form action="{{ route('customer.track') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <input type="text" name="ticket_number" required placeholder="Contoh: SRV-20260804-001 / 081234567890"
                           class="w-full px-5 py-4 rounded-2xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-center font-bold text-base text-slate-900 placeholder:font-normal placeholder:text-slate-400 bg-slate-50 transition">
                </div>
                <button type="submit" class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-extrabold rounded-2xl text-sm transition shadow-xl shadow-blue-600/30 flex items-center justify-center gap-2">
                    <i class="fas fa-bolt"></i> Lacak Status Live
                </button>
            </form>
        </div>
    </main>

    <!-- Footer -->
    <footer class="py-6 text-center text-xs text-slate-400">
        &copy; {{ date('Y') }} LaptopCare Service Center. All rights reserved.
    </footer>

</body>
</html>
