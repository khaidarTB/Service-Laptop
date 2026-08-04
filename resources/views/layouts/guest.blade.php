<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'LaptopCare') }} - Autentikasi Sistem</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- FontAwesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
        </style>
    </head>
    <body class="bg-gradient-to-br from-blue-50 via-slate-50 to-blue-100/60 min-h-screen text-slate-800 antialiased flex flex-col justify-between p-4 sm:p-6">
        
        <!-- Header Brand Link -->
        <div class="max-w-7xl mx-auto w-full flex justify-between items-center py-2">
            <a href="/" class="text-2xl text-blue-600 font-extrabold tracking-tight flex items-center gap-2">
                <i class="fas fa-laptop-medical"></i> LaptopCare
            </a>
            <a href="/" class="text-xs font-bold text-slate-600 hover:text-blue-600 bg-white/80 backdrop-blur-md px-4 py-2 rounded-xl shadow-xs border border-slate-200/80 transition flex items-center gap-1.5">
                <i class="fas fa-house text-slate-400"></i> Beranda
            </a>
        </div>

        <!-- Main Card Wrapper -->
        <main class="w-full max-w-md mx-auto my-auto py-6">
            <div class="bg-white/90 backdrop-blur-xl p-8 sm:p-10 rounded-3xl shadow-2xl shadow-blue-900/10 border border-slate-200/80 space-y-6">
                {{ $slot }}
            </div>
        </main>

        <!-- Footer -->
        <footer class="py-4 text-center text-xs text-slate-400 font-medium">
            &copy; {{ date('Y') }} LaptopCare Service Center. Modern Service Platform.
        </footer>

    </body>
</html>
