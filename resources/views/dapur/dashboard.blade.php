<x-layouts.dapur title="Dashboard - Dapur Sultaf" pageTitle="Dashboard Dapur">

    {{-- ===================== HERO BAND — identitas clay/terracotta ===================== --}}
    <div class="bg-sultaf-clay-dark bg-sultaf-pattern-dark rounded-2xl p-6 lg:p-8 mb-6 text-white">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 divide-x divide-white/10">
            <div>
                <p class="text-[11px] uppercase tracking-widest text-white/50 mb-1.5">Menunggu Dimasak</p>
                <p class="font-serif text-3xl font-bold text-sultaf-gold-light">{{ $stats['pending'] }}</p>
            </div>
            <div class="pl-6">
                <p class="text-[11px] uppercase tracking-widest text-white/50 mb-1.5">Sedang Dimasak</p>
                <p class="font-serif text-3xl font-bold">{{ $stats['cooking'] }}</p>
            </div>
            <div class="pl-6">
                <p class="text-[11px] uppercase tracking-widest text-white/50 mb-1.5">Total Menu</p>
                <p class="font-serif text-3xl font-bold">{{ $stats['total_menu'] }}</p>
            </div>
            <div class="pl-6">
                <p class="text-[11px] uppercase tracking-widest text-white/50 mb-1.5">Menu Habis</p>
                <p class="font-serif text-3xl font-bold {{ $stats['menu_habis'] > 0 ? 'text-red-300' : '' }}">{{ $stats['menu_habis'] }}</p>
            </div>
        </div>
    </div>

    {{-- ===================== QUICK ACTIONS ===================== --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <a href="{{ route('dapur.pesanan') }}" class="staff-card block p-6 !bg-sultaf-clay hover:!bg-sultaf-clay-dark transition-colors">
            <div class="w-12 h-12 rounded-xl bg-white/15 flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 17h18M5 17V9a7 7 0 0 1 14 0v8M8 21h8"/></svg>
            </div>
            <p class="font-serif text-lg font-bold text-white mb-1">Kitchen Display</p>
            <p class="text-sm text-white/70">Lihat & proses pesanan yang menunggu dimasak</p>
        </a>

        <a href="{{ route('dapur.menu.index') }}" class="staff-card block p-6 hover:border-sultaf-clay/40 transition-colors">
            <div class="w-12 h-12 rounded-xl bg-sultaf-warning-soft flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-sultaf-clay-dark" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 3v6a2 2 0 0 0 2 2v10M5 3v6M9 3v6M7 11V3"/><path d="M19 3c-2.5 1-3.5 4-2 7l1 2-3 9M19 3l-3 9"/></svg>
            </div>
            <p class="font-serif text-lg font-bold text-sultaf-ink mb-1">Ketersediaan Menu</p>
            <p class="text-sm text-sultaf-muted">Tandai menu yang stoknya habis</p>
        </a>
    </div>
</x-layouts.dapur>
