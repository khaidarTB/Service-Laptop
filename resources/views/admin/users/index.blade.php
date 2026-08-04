<x-app-layout>
    <x-slot name="header">Data Teknisi & Staff System</x-slot>

    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2 rounded-2xl text-xs font-bold transition {{ !$role ? 'bg-blue-600 text-white' : 'bg-white text-slate-600 border border-slate-200' }}">Semua Role</a>
                <a href="{{ route('admin.users.index', ['role' => 'teknisi']) }}" class="px-4 py-2 rounded-2xl text-xs font-bold transition {{ $role === 'teknisi' ? 'bg-blue-600 text-white' : 'bg-white text-slate-600 border border-slate-200' }}">Teknisi</a>
                <a href="{{ route('admin.users.index', ['role' => 'admin']) }}" class="px-4 py-2 rounded-2xl text-xs font-bold transition {{ $role === 'admin' ? 'bg-blue-600 text-white' : 'bg-white text-slate-600 border border-slate-200' }}">Admin</a>
            </div>

            <a href="{{ route('admin.users.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-2xl font-bold text-sm transition shadow-lg shadow-blue-600/30 flex items-center justify-center gap-2">
                <i class="fas fa-user-plus"></i> + Tambah User / Teknisi
            </a>
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
                                    <a href="{{ route('admin.users.edit', $u->id) }}" class="p-2 text-slate-500 hover:text-blue-600 transition"><i class="fas fa-edit"></i></a>
                                    @if($u->id !== auth()->id())
                                        <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus akun pengguna ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 transition"><i class="fas fa-trash"></i></button>
                                        </form>
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
    </div>
</x-app-layout>
