<x-app-layout>
    <x-slot name="header">Data Customer / Pelanggan</x-slot>

    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <form action="{{ route('admin.customers.index') }}" method="GET" class="flex items-center gap-2 max-w-md flex-1">
                <div class="relative w-full">
                    <i class="fas fa-search absolute left-3.5 top-3 text-slate-400"></i>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama, WhatsApp, alamat..."
                           class="w-full pl-10 pr-4 py-2.5 rounded-2xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-sm font-medium bg-white">
                </div>
                <button type="submit" class="px-4 py-2.5 bg-slate-800 text-white rounded-2xl text-sm font-bold hover:bg-slate-900 transition">Cari</button>
            </form>

            <a href="{{ route('admin.customers.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-2xl font-bold text-sm transition shadow-lg shadow-blue-600/30 flex items-center justify-center gap-2">
                <i class="fas fa-user-plus"></i> + Tambah Customer
            </a>
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
                                    <a href="{{ route('admin.customers.edit', $c->id) }}" class="p-2 text-slate-500 hover:text-blue-600 transition"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('admin.customers.destroy', $c->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data customer ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 transition"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
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
    </div>
</x-app-layout>
