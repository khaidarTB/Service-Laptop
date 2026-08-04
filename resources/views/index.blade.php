<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LaptopCare - Website Manajemen & Servis Laptop Profesional</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-slate-50 font-sans text-slate-800 antialiased selection:bg-blue-600 selection:text-white"
      x-data="cartComponent()">

    <!-- NAVIGATION BAR -->
    <nav class="bg-white/90 backdrop-blur-md sticky top-0 z-40 border-b border-slate-100 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-blue-600 text-white flex items-center justify-center font-black shadow-lg shadow-blue-600/30 text-xl">
                        <i class="fas fa-laptop-medical"></i>
                    </div>
                    <span class="text-2xl text-blue-600 font-extrabold tracking-tight">Laptop<span class="text-slate-900">Care</span></span>
                </div>

                <div class="hidden md:flex items-center gap-8 font-semibold text-sm text-slate-600">
                    <a href="#fitur" class="hover:text-blue-600 transition">Keunggulan</a>
                    <a href="#spareparts" class="hover:text-blue-600 transition">Sparepart & Pricing</a>
                    <a href="#daftar" class="hover:text-blue-600 transition">Booking Online</a>
                    <a href="{{ route('customer.trackForm') }}" class="hover:text-blue-600 transition">Lacak Tiket</a>
                </div>

                <div class="flex items-center gap-3">
                    <!-- Floating Cart Drawer Trigger Button -->
                    <button @click="openCart = true" class="relative bg-blue-50 text-blue-700 hover:bg-blue-100 px-4 py-2.5 rounded-2xl font-bold text-sm transition flex items-center gap-2 border border-blue-200">
                        <i class="fas fa-shopping-cart text-blue-600"></i>
                        <span class="hidden sm:inline">Keranjang Part</span>
                        <span x-show="totalCartItems > 0" class="w-5 h-5 rounded-full bg-blue-600 text-white text-xs flex items-center justify-center font-black" x-text="totalCartItems"></span>
                    </button>

                    @auth
                        <a href="{{ route('dashboard') }}" class="bg-slate-900 text-white px-5 py-2.5 rounded-2xl font-bold text-sm hover:bg-slate-800 transition shadow-lg shadow-slate-900/20">
                            Dashboard ({{ auth()->user()->role }})
                        </a>
                    @else
                        <a href="/login" class="bg-blue-600 text-white px-5 py-2.5 rounded-2xl font-bold text-sm hover:bg-blue-700 transition shadow-lg shadow-blue-600/20">
                            <i class="fas fa-arrow-right-to-bracket mr-1.5"></i> Login System
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <section class="relative overflow-hidden bg-gradient-to-b from-blue-50/50 via-slate-50 to-white pt-12 pb-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid lg:grid-cols-12 gap-12 items-center">
            <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                <div class="inline-flex items-center gap-2 py-2 px-4 rounded-full text-xs font-bold bg-blue-100/80 text-blue-800 border border-blue-200 shadow-xs">
                    <span class="w-2 h-2 bg-blue-600 rounded-full animate-ping"></span>
                    Pusat Servis Laptop Terpercaya & Bergaransi Resmi
                </div>

                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-slate-900 tracking-tight leading-tight">
                    Solusi Perbaikan Laptop <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 via-indigo-600 to-blue-800">Cepat, Transparan & Real-Time.</span>
                </h1>

                <p class="text-base sm:text-lg text-slate-500 max-w-2xl mx-auto lg:mx-0 leading-relaxed">
                    Layanan servis profesional untuk Asus, Lenovo, Acer, HP, Dell, & Macbook. Dilengkapi fitur <b>Live Tracking Progress</b>, sparepart original, serta estimasi biaya yang jelas sejak awal.
                </p>

                <!-- Quick Tracking Form Widget in Hero -->
                <div class="bg-white p-3 sm:p-4 rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-100 max-w-xl mx-auto lg:mx-0">
                    <form action="{{ route('customer.track') }}" method="POST" class="flex flex-col sm:flex-row gap-3">
                        @csrf
                        <div class="relative flex-1">
                            <i class="fas fa-search absolute left-4 top-3.5 text-slate-400"></i>
                            <input type="text" name="ticket_number" required placeholder="Masukkan No Tiket (Contoh: SRV-20260804-001) / WA"
                                   class="w-full pl-11 pr-4 py-3 rounded-2xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-sm font-medium transition bg-slate-50">
                        </div>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-3 rounded-2xl text-sm transition shadow-lg shadow-blue-600/30 flex items-center justify-center gap-2">
                            <i class="fas fa-bolt"></i> Lacak Live
                        </button>
                    </form>
                </div>

                <div class="flex flex-wrap gap-6 justify-center lg:justify-start pt-4 text-slate-600 text-xs sm:text-sm font-semibold">
                    <span class="flex items-center gap-2"><i class="fas fa-check-circle text-blue-600"></i> Garansi 30 Hari</span>
                    <span class="flex items-center gap-2"><i class="fas fa-check-circle text-blue-600"></i> Part Original 100%</span>
                    <span class="flex items-center gap-2"><i class="fas fa-check-circle text-blue-600"></i> Notifikasi WhatsApp</span>
                </div>
            </div>

            <!-- Hero Visual Card -->
            <div class="lg:col-span-5 relative">
                <div class="bg-gradient-to-tr from-blue-600 to-indigo-600 rounded-3xl p-8 text-white shadow-2xl relative overflow-hidden space-y-6">
                    <div class="flex justify-between items-center border-b border-white/20 pb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 bg-emerald-400 rounded-full animate-pulse"></div>
                            <span class="text-xs font-bold uppercase tracking-wider text-blue-100">Live Status Workshop</span>
                        </div>
                        <span class="text-xs bg-white/20 px-3 py-1 rounded-full font-semibold">Aktif Hari Ini</span>
                    </div>

                    <div class="space-y-4">
                        <div class="bg-white/10 backdrop-blur-md p-4 rounded-2xl border border-white/10 space-y-2">
                            <div class="flex justify-between text-xs text-blue-100 font-medium">
                                <span>Tiket Terbaru</span>
                                <span class="font-bold text-white">SRV-20260804-001</span>
                            </div>
                            <p class="font-bold text-lg">Asus ROG Strix G531</p>
                            <div class="flex items-center justify-between pt-1">
                                <span class="bg-emerald-400/20 text-emerald-300 text-xs font-bold px-2.5 py-1 rounded-lg border border-emerald-400/30">
                                    <i class="fas fa-check-circle mr-1"></i> Perbaikan Selesai
                                </span>
                                <span class="text-xs text-blue-200">Teknisi Budi</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-white/10 backdrop-blur-md p-4 rounded-2xl border border-white/10">
                                <p class="text-xs text-blue-200 font-medium">Total Selesai</p>
                                <p class="text-3xl font-black mt-1">150+</p>
                                <p class="text-[10px] text-blue-200/80 mt-0.5">Unit Bulan Ini</p>
                            </div>
                            <div class="bg-white/10 backdrop-blur-md p-4 rounded-2xl border border-white/10">
                                <p class="text-xs text-blue-200 font-medium">Kepuasan</p>
                                <p class="text-3xl font-black mt-1">4.9 ★</p>
                                <p class="text-[10px] text-blue-200/80 mt-0.5">Ulasan Pelanggan</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SPAREPARTS CATALOG & INTERACTIVE CART SECTION -->
    <section id="spareparts" class="py-20 bg-white border-y border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-12 gap-4">
                <div>
                    <span class="text-xs font-bold text-blue-600 uppercase tracking-widest bg-blue-50 px-3 py-1 rounded-lg border border-blue-200">Katalog Part & Komponen</span>
                    <h2 class="text-3xl sm:text-4xl font-black text-slate-900 mt-2">Pilih Sparepart & Estimasi Biaya</h2>
                    <p class="text-slate-500 text-sm mt-1">Klik "+ Keranjang" untuk menambahkan sparepart yang ingin Anda ganti pada formulir booking!</p>
                </div>

                <button @click="openCart = true" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-3 rounded-2xl text-sm transition shadow-lg shadow-blue-600/30 flex items-center gap-2">
                    <i class="fas fa-shopping-bag"></i> Lihat Keranjang Saya (<span x-text="totalCartItems"></span>)
                </button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($spareparts as $part)
                    <div class="bg-slate-50/70 border border-slate-200/80 rounded-3xl p-5 hover:shadow-xl hover:bg-white transition duration-300 flex flex-col justify-between group">
                        <div class="space-y-4">
                            <div class="aspect-video w-full rounded-2xl overflow-hidden bg-slate-200 relative">
                                <img src="{{ $part->image ?? 'https://images.unsplash.com/photo-1597872200969-2b65d56bd16b?w=500' }}" alt="{{ $part->part_name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                <span class="absolute top-3 right-3 bg-white/90 backdrop-blur text-slate-800 font-bold text-xs px-2.5 py-1 rounded-xl shadow-xs">
                                    Stok: {{ $part->stock }}
                                </span>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-900 text-lg group-hover:text-blue-600 transition">{{ $part->part_name }}</h3>
                                <p class="text-xs text-slate-500 line-clamp-2 mt-1">{{ $part->description ?? 'Sparepart berkualitas original untuk penggantian laptop.' }}</p>
                            </div>
                        </div>

                        <div class="pt-6 mt-4 border-t border-slate-200/60 flex items-center justify-between">
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Harga Part</span>
                                <span class="text-lg font-black text-blue-600">Rp {{ number_format($part->selling_price, 0, ',', '.') }}</span>
                            </div>
                            <button @click="addToCart({{ json_encode($part) }})"
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-4 py-2.5 rounded-xl text-xs transition shadow-md shadow-blue-600/20 flex items-center gap-1.5">
                                <i class="fas fa-cart-plus"></i> + Keranjang
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- FORM BOOKING SECTION -->
    <section id="daftar" class="py-20 bg-slate-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white p-8 sm:p-12 rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-100">
                <div class="text-center max-w-xl mx-auto mb-10 space-y-2">
                    <span class="text-xs font-bold text-blue-600 uppercase tracking-widest bg-blue-50 px-3 py-1 rounded-lg border border-blue-200">Registrasi Servis Online</span>
                    <h2 class="text-3xl font-black text-slate-900">Formulir Booking Servis Laptop</h2>
                    <p class="text-slate-500 text-sm">Dapatkan Nomor Tiket Otomatis (SRV-YYYYMMDD-001) untuk memantau status perbaikan.</p>
                </div>

                @if(session('success'))
                    <div class="mb-8 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 flex items-start gap-3 shadow-xs">
                        <i class="fas fa-circle-check mt-1 text-emerald-600 text-xl"></i>
                        <div>
                            <h4 class="font-bold text-base">Registrasi Berhasil!</h4>
                            <p class="text-sm mt-0.5">{!! session('success') !!}</p>
                        </div>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="mb-8 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 flex items-start gap-3 shadow-xs">
                        <i class="fas fa-circle-exclamation mt-1 text-rose-600 text-xl"></i>
                        <div>
                            <h4 class="font-bold text-base">Gagal Mendaftar!</h4>
                            <p class="text-sm mt-0.5">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                <form action="{{ route('booking.store') }}" method="POST" class="space-y-8" @submit="attachCartToForm">
                    @csrf
                    <!-- Hidden field containing json payload of selected spareparts -->
                    <input type="hidden" name="cart_items" :value="JSON.stringify(cart)">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Data Customer -->
                        <div class="space-y-5 bg-slate-50/60 p-6 rounded-2xl border border-slate-200/60">
                            <h3 class="font-bold text-slate-900 border-b border-slate-200 pb-3 flex items-center gap-2">
                                <i class="fas fa-user-circle text-blue-600"></i> Data Diri Pelanggan
                            </h3>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Lengkap *</label>
                                <input type="text" name="name" required placeholder="Contoh: Bambang Pamungkas"
                                       class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-sm font-medium bg-white transition"
                                       value="{{ old('name', auth()->check() ? auth()->user()->name : '') }}">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">No. WhatsApp *</label>
                                <input type="text" name="whatsapp" required placeholder="Contoh: 081234567890"
                                       class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-sm font-medium bg-white transition"
                                       value="{{ old('whatsapp') }}">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Alamat Lengkap *</label>
                                <textarea name="address" required rows="3" placeholder="Alamat rumah / kantor..."
                                          class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-sm font-medium bg-white transition">{{ old('address') }}</textarea>
                            </div>
                        </div>

                        <!-- Data Laptop & Keluhan -->
                        <div class="space-y-5 bg-slate-50/60 p-6 rounded-2xl border border-slate-200/60">
                            <h3 class="font-bold text-slate-900 border-b border-slate-200 pb-3 flex items-center gap-2">
                                <i class="fas fa-laptop text-blue-600"></i> Detail Laptop & Keluhan
                            </h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Merek *</label>
                                    <input type="text" name="laptop_brand" required placeholder="Asus / Lenovo"
                                           class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-sm font-medium bg-white transition"
                                           value="{{ old('laptop_brand') }}">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tipe / Seri *</label>
                                    <input type="text" name="laptop_type" required placeholder="ROG G531"
                                           class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-sm font-medium bg-white transition"
                                           value="{{ old('laptop_type') }}">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kelengkapan Dititipkan</label>
                                <input type="text" name="equipment" placeholder="Contoh: Charger, Tas Laptop"
                                       class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-sm font-medium bg-white transition"
                                       value="{{ old('equipment') }}">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Keluhan Kerusakan *</label>
                                <textarea name="complaint" required rows="3" placeholder="Jelaskan kerusakan laptop (misal: Mati total, layar bergaris, tidak bisa cas)..."
                                          class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-sm font-medium bg-white transition">{{ old('complaint') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Selected Cart Summary inside Form -->
                    <div x-show="cart.length > 0" class="p-4 rounded-2xl bg-blue-50 border border-blue-200 space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-bold text-blue-900 uppercase tracking-wider flex items-center gap-1.5">
                                <i class="fas fa-cart-flatbed text-blue-600"></i> Part Terpilih dari Keranjang (<span x-text="cart.length"></span>)
                            </span>
                            <span class="text-sm font-black text-blue-700" x-text="formatRupiah(cartTotal)"></span>
                        </div>
                        <div class="divide-y divide-blue-200/60 max-h-40 overflow-y-auto pr-2">
                            <template x-for="(item, index) in cart" :key="item.id">
                                <div class="py-2 flex justify-between items-center text-xs">
                                    <div>
                                        <span class="font-bold text-slate-800" x-text="item.part_name"></span>
                                        <span class="text-slate-500" x-text="' (' + item.quantity + 'x @ ' + formatRupiah(item.selling_price) + ')'"></span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-black text-blue-700" x-text="formatRupiah(item.selling_price * item.quantity)"></span>
                                        <button type="button" @click="removeFromCart(index)" class="text-rose-500 hover:text-rose-700 font-bold px-1"><i class="fas fa-times"></i></button>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <p class="text-xs text-slate-400">
                            <i class="fas fa-shield-alt text-blue-600"></i> Data Anda aman. Nomor tiket akan dibuat secara otomatis setelah submit.
                        </p>
                        <button type="submit" class="w-full sm:w-auto px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white font-extrabold rounded-2xl shadow-xl shadow-blue-600/30 transition hover:-translate-y-0.5 flex items-center justify-center gap-2">
                            <i class="fas fa-paper-plane"></i> Kirim Form Booking
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- SLIDE-OVER SPAREPART CART DRAWER (MODAL) -->
    <div x-show="openCart" class="relative z-50" x-cloak>
        <!-- Overlay -->
        <div x-show="openCart" x-transition:enter="ease-in-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in-out duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             @click="openCart = false" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>

        <div class="fixed inset-y-0 right-0 max-w-full flex pl-10">
            <div x-show="openCart" x-transition:enter="transform transition ease-in-out duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transform transition ease-in-out duration-300" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
                 class="w-screen max-w-md bg-white shadow-2xl flex flex-col justify-between">
                
                <!-- Drawer Header -->
                <div class="p-6 bg-slate-900 text-white flex items-center justify-between border-b border-slate-800">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-shopping-cart text-blue-400 text-xl"></i>
                        <h3 class="font-extrabold text-lg">Keranjang Sparepart</h3>
                    </div>
                    <button @click="openCart = false" class="text-slate-400 hover:text-white text-lg"><i class="fas fa-times"></i></button>
                </div>

                <!-- Drawer Content -->
                <div class="flex-1 overflow-y-auto p-6 space-y-4">
                    <template x-if="cart.length === 0">
                        <div class="text-center py-16 space-y-3">
                            <div class="w-16 h-16 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center mx-auto text-2xl">
                                <i class="fas fa-box-open"></i>
                            </div>
                            <p class="font-bold text-slate-700">Keranjang Masih Kosong</p>
                            <p class="text-xs text-slate-400 max-w-xs mx-auto">Pilih part dari katalog sparepart di atas untuk menambahkan part pengganti.</p>
                        </div>
                    </template>

                    <template x-for="(item, index) in cart" :key="item.id">
                        <div class="flex gap-4 p-4 rounded-2xl bg-slate-50 border border-slate-200/80 items-center">
                            <img :src="item.image || 'https://images.unsplash.com/photo-1597872200969-2b65d56bd16b?w=200'" class="w-16 h-16 rounded-xl object-cover border border-slate-200">
                            <div class="flex-1">
                                <h4 class="font-bold text-slate-900 text-sm" x-text="item.part_name"></h4>
                                <p class="text-xs font-black text-blue-600 mt-0.5" x-text="formatRupiah(item.selling_price)"></p>
                                
                                <div class="flex items-center gap-3 mt-2">
                                    <button @click="updateQty(index, item.quantity - 1)" class="w-6 h-6 rounded-lg bg-slate-200 text-slate-700 font-bold text-xs hover:bg-slate-300">-</button>
                                    <span class="text-xs font-bold" x-text="item.quantity"></span>
                                    <button @click="updateQty(index, item.quantity + 1)" class="w-6 h-6 rounded-lg bg-slate-200 text-slate-700 font-bold text-xs hover:bg-slate-300">+</button>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="font-black text-slate-900 text-sm block" x-text="formatRupiah(item.selling_price * item.quantity)"></span>
                                <button @click="removeFromCart(index)" class="text-rose-500 hover:text-rose-700 text-xs font-bold mt-2"><i class="fas fa-trash"></i></button>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Drawer Footer -->
                <div class="p-6 bg-slate-50 border-t border-slate-200 space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-bold text-slate-600">Total Biaya Part:</span>
                        <span class="text-xl font-black text-blue-600" x-text="formatRupiah(cartTotal)"></span>
                    </div>

                    <a href="#daftar" @click="openCart = false" class="w-full py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-extrabold rounded-2xl transition shadow-lg shadow-blue-600/30 flex items-center justify-center gap-2">
                        <i class="fas fa-check-circle"></i> Gunakan Part di Booking Form
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <footer class="bg-slate-900 text-slate-400 py-12 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="text-center md:text-left">
                <span class="text-xl text-white font-black tracking-tight flex items-center gap-2 justify-center md:justify-start">
                    <i class="fas fa-laptop-medical text-blue-500"></i> LaptopCare
                </span>
                <p class="text-xs text-slate-500 mt-1">Sistem Manajemen & Pelayanan Servis Laptop Terintegrasi.</p>
            </div>
            <div class="text-xs text-slate-500">
                &copy; {{ date('Y') }} LaptopCare. All rights reserved.
            </div>
        </div>
    </footer>

    <!-- Alpine.js Cart Logic Script -->
    <script>
        function cartComponent() {
            return {
                openCart: false,
                cart: JSON.parse(localStorage.getItem('laptopcare_cart') || '[]'),
                addToCart(item) {
                    let existing = this.cart.find(i => i.id === item.id);
                    if (existing) {
                        existing.quantity++;
                    } else {
                        this.cart.push({
                            id: item.id,
                            part_name: item.part_name,
                            selling_price: parseFloat(item.selling_price),
                            image: item.image,
                            quantity: 1
                        });
                    }
                    this.saveCart();
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: item.part_name + ' ditambahkan ke keranjang!',
                        showConfirmButton: false,
                        timer: 2000
                    });
                },
                updateQty(index, newQty) {
                    if (newQty <= 0) {
                        this.removeFromCart(index);
                    } else {
                        this.cart[index].quantity = newQty;
                        this.saveCart();
                    }
                },
                removeFromCart(index) {
                    this.cart.splice(index, 1);
                    this.saveCart();
                },
                saveCart() {
                    localStorage.setItem('laptopcare_cart', JSON.stringify(this.cart));
                },
                get totalCartItems() {
                    return this.cart.reduce((sum, item) => sum + item.quantity, 0);
                },
                get cartTotal() {
                    return this.cart.reduce((sum, item) => sum + (item.selling_price * item.quantity), 0);
                },
                formatRupiah(num) {
                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(num);
                },
                attachCartToForm() {
                    // cart will be automatically converted to JSON inside hidden input
                }
            }
        }
    </script>
</body>
</html>