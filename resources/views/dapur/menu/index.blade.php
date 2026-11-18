<x-layouts.dapur title="Ketersediaan Menu - Dapur Sultaf" pageTitle="Ketersediaan Menu">

    @if (session('success'))
        <div class="mb-6 bg-sultaf-success-soft border border-sultaf-success/20 text-sultaf-success text-sm rounded-xl px-4 py-3">
            ✓ {{ session('success') }}
        </div>
    @endif

    {{-- ===================== HEADER & SEARCH & STATS ===================== --}}
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-6">
        
        {{-- Search Bar --}}
        <form method="GET" action="{{ route('dapur.menu.index') }}" class="w-full lg:w-80">
            {{-- Pertahankan kategori saat mencari --}}
            @if(request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
            @endif
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nama menu..."
                   class="w-full border border-sultaf-border rounded-xl px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-sultaf-maroon">
        </form>
        
        {{-- Badges Statistik --}}
        <div class="flex items-center gap-3 w-full lg:w-auto">
            <div class="flex-1 lg:flex-none flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-sultaf-success-soft text-sultaf-success font-semibold text-sm border border-sultaf-success/20">
                <span class="w-2 h-2 rounded-full bg-sultaf-success"></span>
                {{ $totalTersedia }} Tersedia
            </div>
            <div class="flex-1 lg:flex-none flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-sultaf-danger-soft text-sultaf-danger font-semibold text-sm border border-sultaf-danger/20">
                <span class="w-2 h-2 rounded-full bg-sultaf-danger"></span>
                {{ $totalHabis }} Habis
            </div>
        </div>
    </div>

        {{-- ===================== FILTER KATEGORI ===================== --}}
    <div class="flex gap-2 mb-6 overflow-x-auto pb-2 scrollbar-hide">
        {{-- Tambahkan ['search' => request('search')] di setiap route --}}
        <a href="{{ route('dapur.menu.index', ['search' => request('search')]) }}" 
           class="px-5 py-2.5 rounded-full text-sm font-semibold whitespace-nowrap transition-colors
                  {{ !$category ? 'bg-sultaf-maroon text-white' : 'bg-white border border-sultaf-border text-sultaf-ink hover:bg-sultaf-cream' }}">
            Semua
        </a>
        <a href="{{ route('dapur.menu.index', ['category' => 'makanan', 'search' => request('search')]) }}" 
           class="px-5 py-2.5 rounded-full text-sm font-semibold whitespace-nowrap transition-colors
                  {{ $category === 'makanan' ? 'bg-sultaf-maroon text-white' : 'bg-white border border-sultaf-border text-sultaf-ink hover:bg-sultaf-cream' }}">
            Makanan
        </a>
        <a href="{{ route('dapur.menu.index', ['category' => 'minuman', 'search' => request('search')]) }}" 
           class="px-5 py-2.5 rounded-full text-sm font-semibold whitespace-nowrap transition-colors
                  {{ $category === 'minuman' ? 'bg-sultaf-maroon text-white' : 'bg-white border border-sultaf-border text-sultaf-ink hover:bg-sultaf-cream' }}">
            Minuman
        </a>
        <a href="{{ route('dapur.menu.index', ['category' => 'camilan', 'search' => request('search')]) }}" 
           class="px-5 py-2.5 rounded-full text-sm font-semibold whitespace-nowrap transition-colors
                  {{ $category === 'camilan' ? 'bg-sultaf-maroon text-white' : 'bg-white border border-sultaf-border text-sultaf-ink hover:bg-sultaf-cream' }}">
            Camilan
        </a>
    </div>

    {{-- ===================== GRID MENU ===================== --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
        @forelse ($menus as $menu)
            <div class="bg-white rounded-2xl border border-sultaf-border overflow-hidden flex flex-col">
                {{-- Foto Menu --}}
                <div class="h-40 bg-sultaf-cream flex items-center justify-center overflow-hidden relative shrink-0">
                    @if ($menu->foto_menu)
                        <img src="{{ asset($menu->foto_menu) }}" alt="{{ $menu->nama_menu }}" class="w-full h-full object-cover">
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
                    <p class="text-xs text-sultaf-muted capitalize mb-2">{{ $menu->kategori ?? 'Menu' }}</p>
                    
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