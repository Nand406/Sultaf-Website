<x-layouts.dapur title="Ketersediaan Menu - Dapur Sultaf" pageTitle="Menu Availability">

    @if (session('success'))
        <div class="mb-6 bg-sultaf-success-soft border border-sultaf-success/20 text-sultaf-success text-sm rounded-xl px-4 py-3">
            ✓ {{ session('success') }}
        </div>
    @endif

    {{-- Header & Search --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <form method="GET" action="{{ route('dapur.menu.index') }}" class="w-full sm:w-80">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nama menu..."
                   class="w-full border border-sultaf-border rounded-xl px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-sultaf-maroon">
        </form>
        <p class="text-sm text-sultaf-muted">
            <strong class="text-sultaf-maroon">{{ $totalTersedia ?? 0 }}</strong> tersedia • 
            <strong class="text-sultaf-danger">{{ $totalHabis ?? 0 }}</strong> habis
        </p>
    </div>

    {{-- Grid Menu --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
        @forelse ($menus as $menu)
            <div class="bg-white rounded-2xl border border-sultaf-border overflow-hidden flex flex-col">
                {{-- Foto Menu --}}
                <div class="h-40 bg-sultaf-cream flex items-center justify-center overflow-hidden relative">
                    @if ($menu->foto_menu)
                        <img src="{{ asset('storage/' . $menu->foto_menu) }}" alt="{{ $menu->nama_menu }}" class="w-full h-full object-cover">
                    @else
                        <svg class="w-12 h-12 text-sultaf-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    @endif

                    {{-- Badge Status --}}
                    <span class="absolute top-3 right-3 text-xs font-semibold px-2.5 py-1 rounded-full
                        {{ $menu->status_ketersediaan === 'tersedia' 
                            ? 'bg-sultaf-success-soft text-sultaf-success' 
                            : 'bg-sultaf-danger-soft text-sultaf-danger' }}">
                        {{ ucfirst($menu->status_ketersediaan) }}
                    </span>
                </div>

                {{-- Info Menu --}}
                <div class="p-4 flex-1 flex flex-col">
                    <h3 class="font-semibold text-sultaf-ink mb-1">{{ $menu->nama_menu }}</h3>
                    
                    <p class="text-sm text-sultaf-maroon font-bold mb-4">
                        Rp {{ number_format($menu->harga, 0, ',', '.') }}
                    </p>

                    {{-- Tombol Toggle --}}
                    <form method="POST" action="{{ route('dapur.menu.toggle', $menu) }}" class="mt-auto">
                        @csrf
                        @if ($menu->status_ketersediaan === 'tersedia')
                            <button type="submit" class="w-full bg-sultaf-danger-soft hover:bg-sultaf-danger text-sultaf-danger hover:text-white text-sm font-semibold rounded-xl py-2.5 transition-colors">
                                Tandai Habis
                            </button>
                        @else
                            <button type="submit" class="w-full bg-sultaf-success-soft hover:bg-sultaf-success text-sultaf-success hover:text-white text-sm font-semibold rounded-xl py-2.5 transition-colors">
                                Tandai Tersedia
                            </button>
                        @endif
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center text-sultaf-muted py-16">
                <p>Tidak ada menu ditemukan.</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if ($menus->hasPages())
        <div class="mt-6">{{ $menus->links() }}</div>
    @endif

</x-layouts.dapur>