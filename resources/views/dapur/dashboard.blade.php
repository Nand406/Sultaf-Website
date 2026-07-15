<x-layouts.dapur title="Dashboard - Dapur Sultaf" pageTitle="Dashboard Dapur">

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <div class="bg-white rounded-2xl p-5 border border-gray-100">
            <p class="text-xs text-gray-400 uppercase tracking-wide mb-2">Menunggu Dimasak</p>
            <p class="font-serif text-3xl font-bold text-amber-600">{{ $stats['pending'] }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100">
            <p class="text-xs text-gray-400 uppercase tracking-wide mb-2">Sedang Dimasak</p>
            <p class="font-serif text-3xl font-bold text-orange-600">{{ $stats['cooking'] }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100">
            <p class="text-xs text-gray-400 uppercase tracking-wide mb-2">Menu Tidak Tersedia</p>
            <p class="font-serif text-3xl font-bold text-red-500">{{ $stats['menu_habis'] }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100">
            <p class="text-xs text-gray-400 uppercase tracking-wide mb-2">Total Menu</p>
            <p class="font-serif text-3xl font-bold text-gray-800">{{ $stats['total_menu'] }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <a href="{{ route('dapur.pesanan') }}" class="bg-orange-600 hover:bg-orange-700 text-white rounded-2xl p-6 flex items-center gap-4 transition-colors">
            <div class="w-12 h-12 rounded-xl bg-white/15 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 17h18M5 17V9a7 7 0 0 1 14 0v8M8 21h8"/></svg>
            </div>
            <div>
                <p class="font-semibold text-lg">Buka Kitchen Display</p>
                <p class="text-sm text-white/70">Kelola status pesanan yang perlu dimasak</p>
            </div>
        </a>
        <a href="{{ route('dapur.menu.index') }}" class="bg-white border border-gray-200 rounded-2xl p-6 flex items-center gap-4 hover:border-orange-400 transition-colors">
            <div class="w-12 h-12 rounded-xl bg-orange-50 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-orange-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 3v6a2 2 0 0 0 2 2v10M5 3v6M9 3v6M7 11V3"/><path d="M19 3c-2.5 1-3.5 4-2 7l1 2-3 9M19 3l-3 9"/></svg>
            </div>
            <div>
                <p class="font-semibold text-lg text-gray-800">Ketersediaan Menu</p>
                <p class="text-sm text-gray-400">Tandai menu tersedia / tidak tersedia</p>
            </div>
        </a>
    </div>
</x-layouts.dapur>
