<x-guest-layout>
    <div class="text-center space-y-2">
        <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mx-auto text-xl font-black shadow-inner">
            <i class="fas fa-user-plus"></i>
        </div>
        <h2 class="text-2xl font-black text-slate-900 tracking-tight">Daftar Akun Customer</h2>
        <p class="text-xs text-slate-500 font-medium">Buat akun untuk memantau status servis dan riwayat laptop Anda.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name -->
        <div class="space-y-1">
            <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Nama Lengkap *</label>
            <div class="relative">
                <i class="fas fa-user absolute left-4 top-3.5 text-slate-400 text-sm"></i>
                <input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name"
                       placeholder="Contoh: Budi Santoso"
                       class="w-full pl-11 pr-4 py-3 rounded-2xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-sm font-medium text-slate-900 bg-slate-50/50 transition">
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-1 text-xs text-rose-600" />
        </div>

        <!-- WhatsApp Number -->
        <div class="space-y-1">
            <label for="whatsapp" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">No. WhatsApp *</label>
            <div class="relative">
                <i class="fab fa-whatsapp absolute left-4 top-3.5 text-emerald-500 text-base"></i>
                <input id="whatsapp" type="text" name="whatsapp" :value="old('whatsapp')" required
                       placeholder="Contoh: 081234567890"
                       class="w-full pl-11 pr-4 py-3 rounded-2xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-sm font-medium text-slate-900 bg-slate-50/50 transition">
            </div>
            <x-input-error :messages="$errors->get('whatsapp')" class="mt-1 text-xs text-rose-600" />
        </div>

        <!-- Address -->
        <div class="space-y-1">
            <label for="address" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Alamat Rumah (Opsional)</label>
            <div class="relative">
                <i class="fas fa-location-dot absolute left-4 top-3.5 text-slate-400 text-sm"></i>
                <input id="address" type="text" name="address" :value="old('address')"
                       placeholder="Jl. Merdeka No. 45, Jakarta"
                       class="w-full pl-11 pr-4 py-3 rounded-2xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-sm font-medium text-slate-900 bg-slate-50/50 transition">
            </div>
            <x-input-error :messages="$errors->get('address')" class="mt-1 text-xs text-rose-600" />
        </div>

        <!-- Email Address -->
        <div class="space-y-1">
            <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Email Login *</label>
            <div class="relative">
                <i class="fas fa-envelope absolute left-4 top-3.5 text-slate-400 text-sm"></i>
                <input id="email" type="email" name="email" :value="old('email')" required autocomplete="username"
                       placeholder="customer@gmail.com"
                       class="w-full pl-11 pr-4 py-3 rounded-2xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-sm font-medium text-slate-900 bg-slate-50/50 transition">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs text-rose-600" />
        </div>

        <!-- Password -->
        <div class="space-y-1">
            <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Password *</label>
            <div class="relative">
                <i class="fas fa-lock absolute left-4 top-3.5 text-slate-400 text-sm"></i>
                <input id="password" type="password" name="password" required autocomplete="new-password"
                       placeholder="Minimal 8 karakter"
                       class="w-full pl-11 pr-4 py-3 rounded-2xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-sm font-medium text-slate-900 bg-slate-50/50 transition">
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs text-rose-600" />
        </div>

        <!-- Confirm Password -->
        <div class="space-y-1">
            <label for="password_confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Konfirmasi Password *</label>
            <div class="relative">
                <i class="fas fa-lock-keyhole absolute left-4 top-3.5 text-slate-400 text-sm"></i>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                       placeholder="Ulangi password"
                       class="w-full pl-11 pr-4 py-3 rounded-2xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-sm font-medium text-slate-900 bg-slate-50/50 transition">
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-xs text-rose-600" />
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-extrabold rounded-2xl text-sm transition shadow-lg shadow-blue-600/30 flex items-center justify-center gap-2 mt-2">
            <i class="fas fa-user-plus"></i> Daftar Akun Customer
        </button>

        <!-- Login Link Footer -->
        <div class="pt-4 border-t border-slate-100 text-center text-xs text-slate-600">
            Sudah memiliki akun?
            <a href="{{ route('login') }}" class="font-extrabold text-blue-600 hover:underline">
                Login di sini
            </a>
        </div>
    </form>
</x-guest-layout>
