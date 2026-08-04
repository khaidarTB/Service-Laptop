<x-guest-layout>
    <div class="text-center space-y-2">
        <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mx-auto text-xl font-black shadow-inner">
            <i class="fas fa-right-to-bracket"></i>
        </div>
        <h2 class="text-2xl font-black text-slate-900 tracking-tight">Selamat Datang Kembali</h2>
        <p class="text-xs text-slate-500 font-medium">Masuk untuk mengelola servis atau memantau progres perbaikan laptop Anda.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4 text-xs font-bold text-emerald-600 bg-emerald-50 p-3 rounded-xl border border-emerald-200" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div class="space-y-1.5">
            <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Email Akun *</label>
            <div class="relative">
                <i class="fas fa-envelope absolute left-4 top-3.5 text-slate-400 text-sm"></i>
                <input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username"
                       placeholder="nama@email.com"
                       class="w-full pl-11 pr-4 py-3 rounded-2xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-sm font-medium text-slate-900 bg-slate-50/50 transition">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs text-rose-600" />
        </div>

        <!-- Password -->
        <div class="space-y-1.5">
            <div class="flex justify-between items-center">
                <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Password *</label>
                @if (Route::has('password.request'))
                    <a class="text-xs font-bold text-blue-600 hover:underline" href="{{ route('password.request') }}">
                        Lupa Password?
                    </a>
                @endif
            </div>
            <div class="relative">
                <i class="fas fa-lock absolute left-4 top-3.5 text-slate-400 text-sm"></i>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                       placeholder="••••••••"
                       class="w-full pl-11 pr-4 py-3 rounded-2xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-sm font-medium text-slate-900 bg-slate-50/50 transition">
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs text-rose-600" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between pt-1">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500" name="remember">
                <span class="ms-2 text-xs font-semibold text-slate-600">Ingat Saya di Perangkat Ini</span>
            </label>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-extrabold rounded-2xl text-sm transition shadow-lg shadow-blue-600/30 flex items-center justify-center gap-2">
            <i class="fas fa-right-to-bracket"></i> Masuk ke Portal
        </button>

        <!-- Demo Accounts Hint Box -->
        <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200/80 text-[11px] text-slate-500 space-y-1">
            <span class="font-bold text-slate-700 block uppercase text-[10px]">Akun Pengujian Demo (Seeded):</span>
            <div class="flex justify-between">
                <span>Admin: <b class="text-slate-800">admin@laptopcare.com</b></span>
                <span>Pass: <b class="text-slate-800">password</b></span>
            </div>
            <div class="flex justify-between">
                <span>Teknisi: <b class="text-slate-800">budi.teknisi@laptopcare.com</b></span>
                <span>Pass: <b class="text-slate-800">password</b></span>
            </div>
            <div class="flex justify-between">
                <span>Customer: <b class="text-slate-800">andi.pratama@gmail.com</b></span>
                <span>Pass: <b class="text-slate-800">password</b></span>
            </div>
        </div>

        <!-- Register Link Footer -->
        <div class="pt-4 border-t border-slate-100 text-center text-xs text-slate-600">
            Belum memiliki akun customer?
            <a href="{{ route('register') }}" class="font-extrabold text-blue-600 hover:underline">
                Daftar Akun Baru
            </a>
        </div>
    </form>
</x-guest-layout>
