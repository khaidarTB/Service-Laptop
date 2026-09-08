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

                @if($errors->any())
                    <div class="mb-8 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 flex items-start gap-3 shadow-xs">
                        <i class="fas fa-circle-exclamation mt-1 text-rose-600 text-xl"></i>
                        <div>
                            <h4 class="font-bold text-base">Terdapat Kesalahan Input!</h4>
                            <ul class="list-disc list-inside text-sm mt-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
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