<x-layouts.admin title="Kelola Menu - Admin Sultaf" pageTitle="Kelola Menu">

    @if (session('success'))
        <div class="mb-6 bg-sultaf-success-soft border border-sultaf-success/20 text-sultaf-success text-sm rounded-xl px-4 py-3">
            ✓ {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <form method="GET" class="flex-1 max-w-sm">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama menu..."
                   class="w-full border border-sultaf-border rounded-xl px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-sultaf-maroon">
        </form>
        <a href="{{ route('admin.menu.create') }}"
           class="bg-sultaf-maroon hover:bg-sultaf-maroon-dark text-white text-sm font-semibold rounded-xl px-5 py-2.5 flex items-center gap-2 justify-center transition-colors">
            <span class="text-lg leading-none">+</span> Tambah Menu
        </a>
    </div>

    <div class="staff-card overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-sultaf-muted text-xs uppercase bg-sultaf-cream/60">
                    <th class="px-5 py-3 font-medium">Menu</th>
                    <th class="px-5 py-3 font-medium">Kategori</th>
                    <th class="px-5 py-3 font-medium">Harga</th>
                    <th class="px-5 py-3 font-medium">Rating</th>
                    <th class="px-5 py-3 font-medium">Status</th>
                    <th class="px-5 py-3 font-medium text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-sultaf-border">
                @forelse ($menus as $menu)
                    <tr>
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-11 h-11 rounded-lg bg-sultaf-cream-dark overflow-hidden shrink-0">
                                    @if ($menu->foto_makanan)
                                        <img src="{{ asset('storage/'.$menu->foto_makanan) }}" class="w-full h-full object-cover">
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <p class="font-semibold text-sultaf-ink truncate">{{ $menu->nama_makanan }}</p>
                                    <p class="text-xs text-sultaf-muted truncate max-w-[220px]">{{ $menu->deskripsi }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-sultaf-muted">{{ $menu->category->name ?? '-' }}</td>
                        <td class="px-5 py-3 font-medium text-sultaf-maroon">{{ $menu->harga_format }}</td>
                        <td class="px-5 py-3 text-sultaf-muted">⭐ {{ number_format($menu->rating, 1) }}</td>
                        <td class="px-5 py-3">
                            <form method="POST" action="{{ route('admin.menu.toggle', $menu) }}">
                                @csrf
                                <button type="submit"
                                        class="text-xs font-semibold px-2.5 py-1 rounded-full transition-colors
                                               {{ $menu->habis ? 'bg-sultaf-danger-soft text-sultaf-danger hover:opacity-80' : 'bg-sultaf-success-soft text-sultaf-success hover:opacity-80' }}">
                                    {{ $menu->habis ? 'Habis' : 'Tersedia' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-5 py-3">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.menu.edit', $menu) }}"
                                   class="text-xs font-semibold text-blue-600 hover:bg-blue-50 rounded-lg px-3 py-1.5">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('admin.menu.destroy', $menu) }}"
                                      onsubmit="return confirm('Yakin ingin menghapus menu &quot;{{ $menu->nama_makanan }}&quot;?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs font-semibold text-sultaf-danger hover:bg-sultaf-danger-soft rounded-lg px-3 py-1.5">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-5 py-10 text-center text-sultaf-muted">Belum ada menu.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-5">{{ $menus->links() }}</div>
</x-layouts.admin>
