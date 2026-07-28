<x-layouts.dapur title="Ketersediaan Menu - Dapur Sultaf" pageTitle="Menu Availability">

    @if (session('success'))
        <div class="mb-6 bg-sultaf-success-soft border border-sultaf-success/20 text-sultaf-success text-sm rounded-xl px-4 py-3">
            ✓ {{ session('success') }}
        </div>
    @endif

    <form method="GET" class="mb-6 max-w-sm">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama menu..."
               class="w-full border border-sultaf-border rounded-xl px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-sultaf-clay">
    </form>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
        @forelse ($menus as $menu)
            <div class="staff-card overflow-hidden {{ $menu->habis ? 'opacity-60' : '' }}">
                <div class="h-32 bg-sultaf-cream-dark relative">
                    @if ($menu->foto_makanan)
                        <img src="{{ asset('storage/'.$menu->foto_makanan) }}" class="w-full h-full object-cover {{ $menu->habis ? 'grayscale' : '' }}">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-3xl">🍽️</div>
                    @endif
                    @if ($menu->habis)
                        <div class="absolute inset-0 bg-black/50 flex items-center justify-center">
                            <span class="text-white text-xs font-bold uppercase tracking-wide">Habis</span>
                        </div>
                    @endif
                </div>

                <div class="p-4">
                    <p class="font-semibold text-sultaf-ink text-sm mb-1 truncate">{{ $menu->nama_makanan }}</p>
                    <p class="text-xs text-sultaf-muted mb-3">{{ $menu->category->name ?? '-' }}</p>

                    <form method="POST" action="{{ route('dapur.menu.toggle', $menu) }}">
                        @csrf
                        <button type="submit"
                                class="w-full text-xs font-semibold rounded-lg py-2.5 transition-colors
                                       {{ $menu->habis
                                            ? 'bg-sultaf-success text-white hover:opacity-90'
                                            : 'bg-sultaf-danger-soft text-sultaf-danger hover:bg-sultaf-danger hover:text-white' }}">
                            {{ $menu->habis ? 'Tandai Tersedia Lagi' : 'Tandai Habis' }}
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <p class="col-span-full text-center text-sultaf-muted py-16">Tidak ada menu ditemukan.</p>
        @endforelse
    </div>

    <div class="mt-6">{{ $menus->links() }}</div>
</x-layouts.dapur>
