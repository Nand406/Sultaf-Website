<x-layouts.dapur title="Ketersediaan Menu - Dapur Sultaf" pageTitle="Ketersediaan Menu">

    @if (session('success'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-xl px-4 py-3">
            ✓ {{ session('success') }}
        </div>
    @endif

    <div class="flex items-center justify-between mb-6">
        <p class="text-sm text-gray-500">
            Klik status pada tiap menu untuk menandainya tersedia atau tidak tersedia.
            Menu yang ditandai <strong>tidak tersedia</strong> akan tampil abu-abu dan tidak bisa dipesan pelanggan.
        </p>
        @if ($totalHabis > 0)
            <span class="text-xs font-semibold bg-red-50 text-red-600 px-3 py-1.5 rounded-full shrink-0 ml-4">
                {{ $totalHabis }} menu tidak tersedia
            </span>
        @endif
    </div>

    {{-- Tab kategori --}}
    <div class="flex gap-2 mb-5 overflow-x-auto pb-1">
        @foreach ($categories as $cat)
            <a href="{{ route('dapur.menu.index', ['category' => $cat->slug]) }}"
               class="shrink-0 px-4 py-2 rounded-full text-sm font-semibold whitespace-nowrap transition-colors
                      {{ $activeCategory === $cat->slug
                            ? 'bg-orange-600 text-white'
                            : 'bg-white text-gray-600 border border-gray-200' }}">
                {{ $cat->name }}
            </a>
        @endforeach
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse ($menus as $menu)
            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden flex items-center gap-4 p-4
                        {{ $menu->habis ? 'opacity-60' : '' }}">
                <div class="w-16 h-16 rounded-xl bg-sultaf-cream-dark overflow-hidden shrink-0">
                    @if ($menu->foto_makanan)
                        <img src="{{ asset('storage/'.$menu->foto_makanan) }}" class="w-full h-full object-cover {{ $menu->habis ? 'grayscale' : '' }}">
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-gray-800 truncate">{{ $menu->nama_makanan }}</p>
                    <p class="text-xs text-gray-400">{{ $menu->harga_format }}</p>
                </div>
                <form method="POST" action="{{ route('dapur.menu.toggle', $menu) }}" class="shrink-0">
                    @csrf
                    <button type="submit"
                            class="text-xs font-semibold px-3 py-2 rounded-lg transition-colors
                                   {{ $menu->habis ? 'bg-red-50 text-red-600 hover:bg-red-100' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}">
                        {{ $menu->habis ? 'Tidak Tersedia' : 'Tersedia' }}
                    </button>
                </form>
            </div>
        @empty
            <p class="col-span-full text-center text-gray-400 py-16">Tidak ada menu di kategori ini.</p>
        @endforelse
    </div>
</x-layouts.dapur>
