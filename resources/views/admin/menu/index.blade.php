<x-layouts.admin title="Kelola Menu - Admin Sultaf" pageTitle="Kelola Menu">

    @if (session('success'))
        <div class="mb-6 bg-sultaf-success-soft border border-sultaf-success/20 text-sultaf-success text-sm rounded-xl px-4 py-3">
            ✓ {{ session('success') }}
        </div>
    @endif

    {{-- Header dengan Search & Tombol Tambah --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <form method="GET" action="{{ route('admin.menu.index') }}" class="w-full sm:w-80">
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Cari nama menu..." 
                   class="w-full border border-sultaf-border rounded-xl px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-sultaf-maroon">
        </form>
        <a href="{{ route('admin.menu.create') }}" class="bg-sultaf-maroon hover:bg-sultaf-maroon-dark text-white text-sm font-semibold rounded-xl px-5 py-2.5 transition-colors shrink-0">
            + Tambah Menu
        </a>
    </div>

    {{-- Tabel Menu --}}
    <div class="staff-card overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-sultaf-muted text-xs uppercase bg-sultaf-cream/60">
                    <th class="px-5 py-3 font-medium">Menu</th>
                    <th class="px-5 py-3 font-medium">Harga</th>
                    <th class="px-5 py-3 font-medium">Status</th>
                    <th class="px-5 py-3 font-medium text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-sultaf-border">
                @forelse ($menus as $menu)
                    <tr>
                        {{-- KOLOM MENU: Menampilkan Foto (jika ada) dan Nama Menu --}}
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                @if ($menu->foto_menu)
                                    <img src="{{ asset('storage/' . $menu->foto_menu) }}" alt="{{ $menu->nama_menu }}" 
                                         class="w-10 h-10 rounded-lg object-cover border border-sultaf-border">
                                @else
                                    <div class="w-10 h-10 rounded-lg bg-sultaf-cream flex items-center justify-center text-sultaf-muted text-xs">
                                        No Img
                                    </div>
                                @endif
                                <span class="font-semibold text-sultaf-ink">{{ $menu->nama_menu }}</span>
                            </div>
                        </td>

                        {{-- KOLOM HARGA: Menggunakan 'harga', bukan 'harga_makanan' --}}
                        <td class="px-5 py-3 text-sultaf-ink">
                            Rp {{ number_format($menu->harga, 0, ',', '.') }}
                        </td>

                        {{-- KOLOM STATUS --}}
                        <td class="px-5 py-3">
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full
                                {{ $menu->status_ketersediaan === 'tersedia' 
                                    ? 'bg-sultaf-success-soft text-sultaf-success' 
                                    : 'bg-sultaf-danger-soft text-sultaf-danger' }}">
                                {{ ucfirst($menu->status_ketersediaan) }}
                            </span>
                        </td>

                        {{-- KOLOM AKSI --}}
                        <td class="px-5 py-3 text-right">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('admin.menu.edit', $menu) }}" class="text-blue-600 hover:underline text-sm font-medium">Edit</a>
                                <form method="POST" action="{{ route('admin.menu.destroy', $menu) }}" onsubmit="return confirm('Yakin ingin menghapus menu ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sultaf-danger hover:underline text-sm font-medium">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-5 py-12 text-center text-sultaf-muted">
                            Belum ada menu yang ditambahkan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if ($menus->hasPages())
        <div class="mt-5">
            {{ $menus->links() }}
        </div>
    @endif

</x-layouts.admin>