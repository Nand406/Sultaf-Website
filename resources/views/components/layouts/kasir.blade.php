<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Kasir — Sultaf' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-sultaf-cream flex">

    {{-- ===== SIDEBAR — maroon gelap bertekstur, identitas kuat ===== --}}
    <aside class="w-64 shrink-0 bg-sultaf-maroon-dark bg-sultaf-pattern-dark flex flex-col min-h-screen sticky top-0 text-white">
        <div class="px-6 py-6 border-b border-white/10">
            <div class="flex items-center gap-2.5 mb-1">
                <div class="w-8 h-8 rounded-lg bg-sultaf-gold flex items-center justify-center">
                    <svg viewBox="0 0 24 24" class="w-4 h-4 text-sultaf-maroon-dark" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                        <path d="M5 3v6a2 2 0 0 0 2 2v10M5 3v6M9 3v6M7 11V3"/>
                        <path d="M19 3c-2.5 1-3.5 4-2 7l1 2-3 9M19 3l-3 9"/>
                    </svg>
                </div>
                <span class="font-serif text-lg font-bold text-white">Sultaf</span>
            </div>
            <p class="text-[11px] text-sultaf-gold-light font-medium uppercase tracking-widest">{{ __('Cashier Panel') }}</p>
        </div>

        <div class="px-6 py-4 border-b border-white/10">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center">
                    <span class="text-sultaf-gold-light font-bold text-sm">{{ substr(auth()->user()->name, 0, 1) }}</span>
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-white/50 capitalize">{{ auth()->user()->role }}</p>
                </div>
            </div>
        </div>

        <div class="px-4 pt-4">
            @include('components.panel-switcher')
        </div>

        <nav class="flex-1 px-4 py-4 space-y-1">
            @php
                $navItems = [
                    ['label' => __('Dashboard'),   'route' => 'kasir.dashboard',   'icon' => 'dashboard'],
                    ['label' => __('Verification'),  'route' => 'kasir.pembayaran',  'icon' => 'verify'],
                    ['label' => __('Order Status'), 'route' => 'kasir.pesanan', 'icon' => 'orders'],
                    ['label' => __('POS (Offline)'), 'route' => 'kasir.pos',     'icon' => 'pos'],
                ];
            @endphp

            @foreach ($navItems as $item)
                @php $active = request()->routeIs($item['route']); @endphp
                <a href="{{ route($item['route']) }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors
                          {{ $active ? 'bg-sultaf-gold text-sultaf-maroon-dark font-semibold' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    @switch($item['icon'])
                        @case('dashboard')
                            <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                            @break
                        @case('verify')
                            <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/></svg>
                            @break
                        @case('orders')
                            <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="3" width="16" height="18" rx="2"/><path d="M8 7h8M8 11h8M8 15h5"/></svg>
                            @break
                        @case('pos')
                            <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg>
                            @break
                    @endswitch
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="px-4 py-5 border-t border-white/10 space-y-3">
            <div class="px-1">
                @include('components.lang-switch')
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="flex items-center gap-3 w-full px-3.5 py-2.5 rounded-xl text-sm font-medium text-white/60 hover:bg-white/10 hover:text-white transition-colors">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></svg>
                    {{ __('Sign Out') }}
                </button>
            </form>
        </div>
    </aside>

    {{-- ===== KONTEN UTAMA — krem, lebar penuh, tidak dibatasi ===== --}}
    <main class="flex-1 min-w-0 flex flex-col">
        <header class="bg-white/70 backdrop-blur border-b border-sultaf-border px-8 py-5 flex items-center justify-between sticky top-0 z-10">
            <div>
                <h1 class="font-serif text-2xl font-bold text-sultaf-ink">{{ $pageTitle ?? __('Dashboard') }}</h1>
                <p class="text-xs text-sultaf-muted mt-0.5">{{ now()->translatedFormat('l, d F Y') }}</p>
            </div>
            <div class="flex items-center gap-3">
                @php
                    $pendingCount = \App\Models\TransaksiPenjualan::where('status_pembayaran', 'menunggu')->count();
                @endphp
                @if ($pendingCount > 0)
                    <a href="{{ route('kasir.pembayaran') }}"
                       class="flex items-center gap-2 bg-sultaf-warning-soft border border-sultaf-gold/30 text-sultaf-maroon-dark text-xs font-semibold px-4 py-2 rounded-full">
                        <span class="w-2 h-2 rounded-full bg-sultaf-gold animate-pulse"></span>
                        {{ $pendingCount }} {{ __('payments waiting') }}
                    </a>
                @endif
            </div>
        </header>

        <div class="flex-1 p-8">
            {{ $slot }}
        </div>
    </main>
</body>
</html>
