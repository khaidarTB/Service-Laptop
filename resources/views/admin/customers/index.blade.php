<x-app-layout>
    <x-slot name="header">Data Customer / Pelanggan</x-slot>

    @php
        $hasErrors = $errors->any();
        $editMode = $hasErrors && old('customer_id');
        $initialCustomer = $editMode ? [
            'id' => old('customer_id'),
            'name' => old('name'),
            'whatsapp' => old('whatsapp'),
            'address' => old('address'),
        ] : [];
    @endphp

    <div class="space-y-6" x-data="{
        open: @json($hasErrors),
        mode: '{{ $editMode ? 'edit' : 'create' }}',
        customer: @js($initialCustomer),
        openCreate() {
            this.mode = 'create';
            this.customer = {};
            this.open = true;
        },
        openEdit(c) {
            this.mode = 'edit';
            this.customer = { id: c.id, name: c.name, whatsapp: c.whatsapp, address: c.address };
            this.open = true;
        },
        deleteCustomer(id, name) {
            Swal.fire({
                title: 'Hapus Customer?',
                html: `Data <strong>${name}</strong> akan dihapus secara permanen.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                customClass: { popup: 'rounded-3xl' }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    }">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <form action="{{ route('admin.customers.index') }}" method="GET" class="flex items-center gap-2 max-w-md flex-1">
                <div class="relative w-full">
                    <i class="fas fa-search absolute left-3.5 top-3 text-slate-400"></i>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama, WhatsApp, alamat..."
                           class="w-full pl-10 pr-4 py-2.5 rounded-2xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-sm font-medium bg-white">
                </div>
                <button type="submit" class="px-4 py-2.5 bg-slate-800 text-white rounded-2xl text-sm font-bold hover:bg-slate-900 transition">Cari</button>
            </form>

            <button @click="openCreate()" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-2xl font-bold text-sm transition shadow-lg shadow-blue-600/30 flex items-center justify-center gap-2">
                <i class="fas fa-user-plus"></i> + Tambah Customer
            </button>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-700">
                    <thead class="bg-slate-50 border-b border-slate-100 text-xs font-bold uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="py-4 px-6">Pelanggan</th>
                            <th class="py-4 px-6">No. WhatsApp</th>
                            <th class="py-4 px-6">Alamat</th>
                            <th class="py-4 px-6 text-center">Total Servis</th>
                            <th class="py-4 px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @forelse($customers as $c)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 font-bold flex items-center justify-center border border-blue-200">
                                            {{ strtoupper(substr($c->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-900">{{ $c->name }}</p>
                                            @if($c->user)
                                                <span class="text-[10px] text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200 font-semibold">User Account Linked</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $c->whatsapp) }}" target="_blank" class="text-blue-600 font-semibold hover:underline flex items-center gap-1.5">
                                        <i class="fab fa-whatsapp text-emerald-500"></i> {{ $c->whatsapp }}
                                    </a>
                                </td>
                                <td class="py-4 px-6 text-slate-500 max-w-xs truncate">{{ $c->address ?? '-' }}</td>
                                <td class="py-4 px-6 text-center">
                                    <span class="bg-slate-100 text-slate-700 px-3 py-1 rounded-xl text-xs font-bold border border-slate-200">
                                        {{ $c->services_count }} Unit
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right space-x-2">
                                    <button @click="openEdit({{ json_encode($c) }})" class="p-2 text-slate-500 hover:text-blue-600 transition"><i class="fas fa-edit"></i></button>
                                    <button @click="deleteCustomer('{{ $c->id }}', '{{ addslashes($c->name) }}')" class="p-2 text-slate-400 hover:text-rose-600 transition"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>

                            <form id="delete-form-{{ $c->id }}" action="{{ route('admin.customers.destroy', $c->id) }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-slate-400">Belum ada data customer.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $customers->links() }}
            </div>
        </div>

        {{-- Create / Edit Modal --}}
        <div x-show="open" x-transition.opacity x-cloak
             x-effect="if(open) document.body.style.overflow='hidden'; else document.body.style.overflow='';">
            <div class="fixed inset-0 z-50">
                <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="open=false"></div>
                <div class="fixed inset-0 flex items-center justify-center p-4">
                    <div class="bg-white rounded-3xl shadow-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto p-6" @click.stop>
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-black text-slate-900" x-text="mode === 'create' ? 'Tambah Customer Baru' : 'Edit Customer'"></h3>
                            <button @click="open=false" class="text-slate-400 hover:text-slate-600 transition"><i class="fas fa-times text-xl"></i></button>
                        </div>

                        <form :action="mode === 'create' ? '{{ route('admin.customers.store') }}' : '{{ url('admin/customers') }}/' + customer.id"
                              method="POST" class="space-y-4">
                            @csrf
                            <template x-if="mode === 'edit'"><input type="hidden" name="_method" value="PUT"></template>
                            <template x-if="mode === 'edit'"><input type="hidden" name="customer_id" :value="customer.id"></template>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Lengkap *</label>
                                <input type="text" name="name" required
                                       :value="mode === 'edit' ? customer.name : '{{ old('name') }}'"
                                       class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-sm font-medium">
                                @error('name') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">No. WhatsApp *</label>
                                <input type="text" name="whatsapp" required placeholder="0812..."
                                       :value="mode === 'edit' ? customer.whatsapp : '{{ old('whatsapp') }}'"
                                       class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-sm font-medium">
                                @error('whatsapp') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Alamat Lengkap</label>
                                <textarea name="address" rows="3"
                                          x-effect="$el.value = (mode === 'edit') ? (customer.address || '') : '{{ old('address') }}'"
                                          class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-sm font-medium"></textarea>
                                @error('address') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="pt-4 border-t border-slate-100 space-y-4">
                                <p class="text-xs font-bold text-blue-600 uppercase tracking-wider">Opsional: Buat Akun Login Portal Customer</p>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Email Akun</label>
                                    <input type="email" name="email" placeholder="customer@gmail.com"
                                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm">
                                    @error('email') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Password Login</label>
                                    <input type="password" name="password" placeholder="Minimal 6 karakter"
                                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm">
                                    @error('password') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="pt-4 border-t border-slate-100 flex gap-4">
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-3 rounded-2xl text-sm transition flex items-center gap-2">
                                    <i class="fas fa-save"></i>
                                    <span x-text="mode === 'create' ? 'Simpan Customer' : 'Perbarui Customer'"></span>
                                </button>
                                <button type="button" @click="open=false" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold px-6 py-3 rounded-2xl text-sm transition">Batal</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
