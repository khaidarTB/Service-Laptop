<x-app-layout>
    <x-slot name="header">Daftar Tugas Servis Saya</x-slot>

    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <form action="{{ route('teknisi.tasks.index') }}" method="GET" class="flex items-center gap-2 max-w-md flex-1">
                <div class="relative w-full">
                    <i class="fas fa-search absolute left-3.5 top-3 text-slate-400"></i>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari No Tiket, Merek..."
                           class="w-full pl-10 pr-4 py-2.5 rounded-2xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-sm font-medium bg-white">
                </div>
                <button type="submit" class="px-4 py-2.5 bg-slate-800 text-white rounded-2xl text-sm font-bold hover:bg-slate-900 transition">Cari</button>
            </form>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-700">
                    <thead class="bg-slate-50 border-b border-slate-100 text-xs font-bold uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="py-4 px-6">Tiket & Laptop</th>
                            <th class="py-4 px-6">Pelanggan</th>
                            <th class="py-4 px-6">Keluhan</th>
                            <th class="py-4 px-6">Status Perbaikan</th>
                            <th class="py-4 px-6 text-right">Aksi Workshop</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @forelse($tasks as $t)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-4 px-6">
                                    <div>
                                        <a href="{{ route('teknisi.tasks.show', $t->id) }}" class="font-extrabold text-blue-600 hover:underline">
                                            {{ $t->ticket_number }}
                                        </a>
                                        <p class="font-bold text-slate-900 text-sm">{{ $t->laptop_brand }} {{ $t->laptop_type }}</p>
                                    </div>
                                </td>
                                <td class="py-4 px-6 font-semibold text-slate-900">
                                    {{ $t->customer ? $t->customer->name : '-' }}
                                </td>
                                <td class="py-4 px-6 text-slate-500 max-w-xs truncate">{{ $t->complaint }}</td>
                                <td class="py-4 px-6">
                                    <span class="{{ $t->status_badge_class }} px-3 py-1 rounded-xl text-xs font-extrabold border">
                                        {{ $t->status_label }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <a href="{{ route('teknisi.tasks.show', $t->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-4 py-2 rounded-xl text-xs transition inline-flex items-center gap-1">
                                        <i class="fas fa-screwdriver-wrench"></i> Buka Workshop
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-slate-400">Tidak ada tugas perbaikan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $tasks->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
