<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'LaptopCare') }} - Sistem Servis Laptop Modern</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
        
        <!-- FontAwesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        
        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        
        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
            [x-cloak] { display: none !important; }
        </style>
    </head>
    <body class="font-sans antialiased text-slate-800 bg-slate-50/60" x-data="{ sidebarOpen: false }">
        <div class="flex h-screen overflow-hidden">
            
            <!-- Sidebar -->
            <aside class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-white transition-transform duration-300 transform"
                   :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen, 'md:translate-x-0': true}"
                   @click.outside="sidebarOpen = false">
                
                <div class="flex items-center justify-between px-6 h-16 bg-blue-600 border-b border-blue-700">
                    <a href="/" class="text-xl font-extrabold tracking-tight text-white flex items-center gap-2">
                        <i class="fas fa-laptop-medical text-2xl"></i> LaptopCare
                    </a>
                </div>

                <div class="p-4 flex gap-3 items-center border-b border-slate-800">
                    <div class="w-10 h-10 rounded-xl bg-blue-500/20 text-blue-400 border border-blue-500/30 flex items-center justify-center font-bold text-lg shadow-lg">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="overflow-hidden">
                        <p class="font-semibold text-sm text-slate-100 truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-blue-400 capitalize font-medium">{{ auth()->user()->role }}</p>
                    </div>
                </div>

                <nav class="flex-1 px-4 py-4 space-y-1.5 overflow-y-auto">
                    @if(auth()->user()->role === 'admin')
                        <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider px-3 pt-2 pb-1">Utama</p>
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl hover:bg-blue-600 hover:text-white transition group {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30 font-semibold' : 'text-slate-300' }}">
                            <i class="fas fa-chart-pie w-6 text-center group-hover:scale-110 transition"></i> Dashboard
                        </a>
                        
                        <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider px-3 pt-3 pb-1">Master Data</p>
                        <a href="{{ route('admin.customers.index') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl hover:bg-slate-800 hover:text-white transition group {{ request()->routeIs('admin.customers.*') ? 'bg-slate-800 text-white border-l-2 border-blue-500' : 'text-slate-400' }}">
                            <i class="fas fa-users w-6 text-center group-hover:scale-110 transition"></i> Data Customer
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl hover:bg-slate-800 hover:text-white transition group {{ request()->routeIs('admin.users.*') ? 'bg-slate-800 text-white border-l-2 border-blue-500' : 'text-slate-400' }}">
                            <i class="fas fa-user-gear w-6 text-center group-hover:scale-110 transition"></i> Data Teknisi
                        </a>
                        <a href="{{ route('admin.spareparts.index') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl hover:bg-slate-800 hover:text-white transition group {{ request()->routeIs('admin.spareparts.*') ? 'bg-slate-800 text-white border-l-2 border-blue-500' : 'text-slate-400' }}">
                            <i class="fas fa-boxes-packing w-6 text-center group-hover:scale-110 transition"></i> Sparepart Catalog
                        </a>

                        <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider px-3 pt-3 pb-1">Servis & Keuangan</p>
                        <a href="{{ route('admin.services.index') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl hover:bg-slate-800 hover:text-white transition group {{ request()->routeIs('admin.services.*') ? 'bg-slate-800 text-white border-l-2 border-blue-500' : 'text-slate-400' }}">
                            <i class="fas fa-screwdriver-wrench w-6 text-center group-hover:scale-110 transition"></i> Data Servis Tiket
                        </a>
                        <a href="{{ route('admin.transactions.index') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl hover:bg-slate-800 hover:text-white transition group {{ request()->routeIs('admin.transactions.*') ? 'bg-slate-800 text-white border-l-2 border-blue-500' : 'text-slate-400' }}">
                            <i class="fas fa-receipt w-6 text-center group-hover:scale-110 transition"></i> Transaksi Invoice
                        </a>

                        <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider px-3 pt-3 pb-1">Analytics</p>
                        <a href="{{ route('admin.reports.index') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl hover:bg-slate-800 hover:text-white transition group {{ request()->routeIs('admin.reports.*') ? 'bg-slate-800 text-white border-l-2 border-blue-500' : 'text-slate-400' }}">
                            <i class="fas fa-chart-column w-6 text-center group-hover:scale-110 transition"></i> Rekap Laporan
                        </a>

                    @elseif(auth()->user()->role === 'teknisi')
                        <a href="{{ route('teknisi.dashboard') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl hover:bg-blue-600 hover:text-white transition group {{ request()->routeIs('teknisi.dashboard') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30 font-semibold' : 'text-slate-300' }}">
                            <i class="fas fa-home w-6 text-center group-hover:scale-110 transition"></i> Dashboard
                        </a>
                        <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider px-3 pt-3 pb-1">Tugas Servis</p>
                        <a href="{{ route('teknisi.tasks.index') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl hover:bg-slate-800 hover:text-white transition group {{ request()->routeIs('teknisi.tasks.*') ? 'bg-slate-800 text-white border-l-2 border-blue-500' : 'text-slate-400' }}">
                            <i class="fas fa-list-check w-6 text-center group-hover:scale-110 transition"></i> Daftar Servis
                        </a>

                    @elseif(auth()->user()->role === 'customer')
                        <a href="{{ route('customer.dashboard') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl hover:bg-blue-600 hover:text-white transition group {{ request()->routeIs('customer.dashboard') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30 font-semibold' : 'text-slate-300' }}">
                            <i class="fas fa-laptop-medical w-6 text-center group-hover:scale-110 transition"></i> Servis Saya
                        </a>
                        <a href="{{ route('customer.trackForm') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl hover:bg-slate-800 hover:text-white transition group {{ request()->routeIs('customer.trackForm') ? 'bg-slate-800 text-white border-l-2 border-blue-500' : 'text-slate-400' }}">
                            <i class="fas fa-magnifying-glass w-6 text-center group-hover:scale-110 transition"></i> Lacak Tiket
                        </a>
                    @endif
                </nav>

                <div class="p-4 border-t border-slate-800">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center px-4 py-2.5 text-sm font-semibold text-white bg-red-600/90 hover:bg-red-600 rounded-xl transition shadow-lg shadow-red-600/20">
                            <i class="fas fa-right-from-bracket mr-2"></i> Logout
                        </button>
                    </form>
                </div>
            </aside>

            <!-- Main Content Area -->
            <div class="flex flex-col flex-1 w-full md:pl-64">
                
                <!-- Navbar -->
                <header class="bg-white/90 backdrop-blur-md sticky top-0 z-40 border-b border-slate-200/80 shadow-xs">
                    <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                        <div class="flex items-center md:hidden">
                            <button @click="sidebarOpen = true" class="text-slate-500 hover:text-blue-600 focus:outline-none">
                                <i class="fas fa-bars text-xl"></i>
                            </button>
                        </div>
                        
                        <div class="flex-1 md:flex-none">
                            @isset($header)
                                <h1 class="text-lg sm:text-xl font-extrabold text-slate-900 tracking-tight ml-3 md:ml-0">{{ $header }}</h1>
                            @endisset
                        </div>

                        <div class="flex items-center gap-3 sm:gap-4">
                            <!-- Quick Website Link -->
                            <a href="/" target="_blank" class="hidden sm:flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-blue-600 bg-slate-100 hover:bg-blue-50 px-3 py-2 rounded-xl transition">
                                <i class="fas fa-globe"></i> Lihat Website
                            </a>
                            
                            <!-- Profile Dropdown -->
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" class="flex items-center gap-2 text-sm font-semibold text-slate-700 hover:text-blue-600 transition focus:outline-none bg-slate-100/80 px-3 py-1.5 rounded-xl">
                                    <div class="w-7 h-7 rounded-lg bg-blue-600 text-white flex items-center justify-center text-xs font-bold">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </div>
                                    <span class="hidden md:inline">{{ auth()->user()->name }}</span>
                                    <i class="fas fa-chevron-down text-xs text-slate-400"></i>
                                </button>
                                <div x-show="open" @click.outside="open = false" class="absolute right-0 w-52 mt-2 origin-top-right bg-white rounded-2xl shadow-xl ring-1 ring-slate-900/5 focus:outline-none z-50 p-1.5" x-transition>
                                    <div class="px-3 py-2 border-b border-slate-100">
                                        <p class="text-xs text-slate-400">Login sebagai</p>
                                        <p class="text-sm font-bold text-slate-800 truncate">{{ auth()->user()->email }}</p>
                                    </div>
                                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-3 py-2 text-sm text-slate-700 rounded-xl hover:bg-slate-50 transition">
                                        <i class="fas fa-user-circle text-slate-400"></i> Edit Profile
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-sm text-red-600 rounded-xl hover:bg-red-50 transition font-medium">
                                            <i class="fas fa-right-from-bracket"></i> Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Main Content -->
                <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 relative">
                    <!-- Session Status Notifications -->
                    @if (session('success'))
                        <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 flex items-start gap-3 shadow-xs" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 7000)">
                            <i class="fas fa-circle-check mt-1 text-emerald-600 text-lg"></i>
                            <div class="flex-1">
                                <h4 class="font-bold text-sm">Berhasil!</h4>
                                <p class="text-xs sm:text-sm mt-0.5">{!! session('success') !!}</p>
                            </div>
                            <button @click="show = false" class="text-emerald-500 hover:text-emerald-700"><i class="fas fa-times"></i></button>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 flex items-start gap-3 shadow-xs" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 7000)">
                            <i class="fas fa-circle-exclamation mt-1 text-rose-600 text-lg"></i>
                            <div class="flex-1">
                                <h4 class="font-bold text-sm">Peringatan!</h4>
                                <p class="text-xs sm:text-sm mt-0.5">{{ session('error') }}</p>
                            </div>
                            <button @click="show = false" class="text-rose-500 hover:text-rose-700"><i class="fas fa-times"></i></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 flex items-start gap-3 shadow-xs" x-data="{ show: true }" x-show="show">
                            <i class="fas fa-circle-exclamation mt-1 text-rose-600 text-lg"></i>
                            <div class="flex-1">
                                <h4 class="font-bold text-sm">Terdapat Kesalahan Input!</h4>
                                <ul class="list-disc list-inside text-xs sm:text-sm mt-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <button @click="show = false" class="text-rose-500 hover:text-rose-700"><i class="fas fa-times"></i></button>
                        </div>
                    @endif
                    
                    {{ $slot }}
                </main>
            </div>
        </div>
        
        @stack('scripts')
    </body>
</html>
