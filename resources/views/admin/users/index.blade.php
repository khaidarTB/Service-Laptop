<x-app-layout>
    <x-slot name="header">Data Teknisi & Staff System</x-slot>

    <div x-data="{ open: false, mode: 'create', user: {} }" class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2 rounded-2xl text-xs font-bold transition {{ !$role ? 'bg-blue-600 text-white' : 'bg-white text-slate-600 border border-slate-200' }}">Semua Role</a>
                <a href="{{ route('admin.users.index', ['role' => 'teknisi']) }}" class="px-4 py-2 rounded-2xl text-xs font-bold transition {{ $role === 'teknisi' ? 'bg-blue-600 text-white' : 'bg-white text-slate-600 border border-slate-200' }}">Teknisi</a>
                <a href="{{ route('admin.users.index', ['role' => 'admin']) }}" class="px-4 py-2 rounded-2xl text-xs font-bold transition {{ $role === 'admin' ? 'bg-blue-600 text-white' : 'bg-white text-slate-600 border border-slate-200' }}">Admin</a>
            </div>

            <button @click="mode='create'; user={}; open=true" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-2xl font-bold text-sm transition shadow-lg shadow-blue-600/30 flex items-center justify-center gap-2">
                <i class="fas fa-user-plus"></i> + Tambah User / Teknisi
            </button>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-700">
                    <thead class="bg-slate-50 border-b border-slate-100 text-xs font-bold uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="py-4 px-6">Nama Staff</th>
                            <th class="py-4 px-6">Email Login</th>
                            <th class="py-4 px-6">Role / Hak Akses</th>
                            <th class="py-4 px-6">Tgl Dibuat</th>
                            <th class="py-4 px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @forelse($users as $u)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-blue-600 text-white font-bold flex items-center justify-center shadow-xs">
                                            {{ strtoupper(substr($u->name, 0, 1)) }}
                                        </div>
                                        <span class="font-bold text-slate-900">{{ $u->name }}</span>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-slate-600 font-semibold">{{ $u->email }}</td>
                                <td class="py-4 px-6">
                                    <span class="px-3 py-1 rounded-xl text-xs font-bold border capitalize {{ $u->role === 'admin' ? 'bg-indigo-50 text-indigo-700 border-indigo-200' : ($u->role === 'teknisi' ? 'bg-cyan-50 text-cyan-700 border-cyan-200' : 'bg-slate-100 text-slate-700 border-slate-200') }}">
                                        {{ $u->role }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-xs text-slate-400">{{ $u->created_at ? $u->created_at->format('d M Y') : '-' }}</td>
                                <td class="py-4 px-6 text-right space-x-2">
                                    <button @click="mode='edit'; user={{ json_encode($u) }}; open=true" class="p-2 text-slate-500 hover:text-blue-600 transition"><i class="fas fa-edit"></i></button>
                                    @if($u->id !== auth()->id())
                                        <button type="button" onclick="confirmDelete({{ $u->id }}, '{{ addslashes($u->name) }}')" class="p-2 text-slate-400 hover:text-rose-600 transition"><i class="fas fa-trash"></i></button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-slate-400">Belum ada data user.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $users->links() }}
            </div>
        </div>

        <div x-show="open" x-transition.opacity class="fixed inset-0 z-50 hidden">
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="open=false"></div>
            <div class="fixed inset-0 flex items-center justify-center p-4">
                <div class="bg-white rounded-3xl shadow-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto p-6" @click.stop>
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-black text-slate-900" x-text="mode==='create' ? 'Tambah User Baru' : 'Edit User'"></h3>
                        <button @click="open=false" class="text-slate-400 hover:text-slate-600"><i class="fas fa-times text-xl"></i></button>
                    </div>

                    <form :action="mode==='create' ? '{{ route('admin.users.store') }}' : '{{ url('admin/users') }}/' + user.id" method="POST" class="space-y-4">
                        @csrf
                        <template x-if="mode==='edit'"><input type="hidden" name="_method" value="PUT"></template>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Pengguna *</label>
                            <input :value="user.name || ''" name="name" type="text" required class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-sm font-medium">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Email Login *</label>
                            <input :value="user.email || ''" name="email" type="email" required placeholder="teknisi@laptopcare.com" class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-sm font-medium">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Password *</label>
                            <input name="password" type="password" :required="mode==='create'" :placeholder="mode==='edit' ? 'Kosongkan jika tidak diubah' : ''" class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-sm font-medium">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Hak Akses Role *</label>
                            <select name="role" required class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-sm font-medium bg-white">
                                <option value="teknisi" :selected="user.role === 'teknisi'">Teknisi</option>
                                <option value="admin" :selected="user.role === 'admin'">Admin</option>
                                <option value="customer" :selected="user.role === 'customer'">Customer</option>
                            </select>
                        </div>

                        <div class="pt-4 border-t border-slate-100 flex gap-4">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-3 rounded-2xl text-sm transition" x-text="mode==='create' ? 'Simpan Pengguna' : 'Perbarui Pengguna'"></button>
                            <button type="button" @click="open=false" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold px-6 py-3 rounded-2xl text-sm transition">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @foreach($users as $u)
            <form id="delete-form-{{ $u->id }}" action="{{ route('admin.users.destroy', $u->id) }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        @endforeach
    </div>

    <script>
        function confirmDelete(userId, userName) {
            Swal.fire({
                title: 'Hapus User?',
                html: `Akun <strong>${userName}</strong> akan dihapus permanen.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + userId).submit();
                }
            });
        }
    </script>
</x-app-layout>
