<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Admin — Sultaf' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-sultaf-cream flex">

    {{-- ===== SIDEBAR — maroon, identitas inti brand ===== --}}
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
            <p class="text-[11px] text-sultaf-gold-light font-medium uppercase tracking-widest">{{ __('Admin Management') }}</p>
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
                    ['label' => __('Dashboard'),        'route' => 'admin.dashboard',      'icon' => 'dashboard'],
                    ['label' => __('Manage Menu'),       'route' => 'admin.menu.index',     'icon' => 'menu'],
                    ['label' => __('Promotions & Benefits'),  'route' => 'admin.benefit.index',  'icon' => 'benefit'],
                    ['label' => __('Send Promo Message'),  'route' => 'admin.promo.index',    'icon' => 'message'],
                ];
            @endphp

            @foreach ($navItems as $item)
                @php $active = request()->routeIs($item['route'] . '*'); @endphp
                <a href="{{ route($item['route']) }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-colors
                          {{ $active ? 'bg-sultaf-gold text-sultaf-maroon-dark font-semibold' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    @switch($item['icon'])
                        @case('dashboard')
                            <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                            @break
                        @case('menu')
                            <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 3v6a2 2 0 0 0 2 2v10M5 3v6M9 3v6M7 11V3"/><path d="M19 3c-2.5 1-3.5 4-2 7l1 2-3 9M19 3l-3 9"/></svg>
                            @break
                        @case('benefit')
                            <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="5"/><path d="M8.5 13 7 21l5-2.5L17 21l-1.5-8"/></svg>
                            @break
                        @case('message')
                            <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.4 8.4 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.4 8.4 0 0 1-3.8-.9L3 21l1.9-5.7a8.4 8.4 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.4 8.4 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                            @break
                    @endswitch
                    {{ $item['label'] }}
                </a>
            @endforeach

            <div class="pt-3 mt-3 border-t border-white/10">
                <a href="{{ route('kasir.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium text-white/50 hover:bg-white/10 hover:text-white">
                    <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg>
                    {{ __('Open Cashier Panel') }}
                </a>
            </div>
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

    {{-- ===== KONTEN UTAMA ===== --}}
    <main class="flex-1 min-w-0 flex flex-col">
        <header class="bg-white/70 backdrop-blur border-b border-sultaf-border px-8 py-5 flex items-center justify-between sticky top-0 z-10">
            <div>
                <h1 class="font-serif text-2xl font-bold text-sultaf-ink">{{ $pageTitle ?? __('Dashboard') }}</h1>
                <p class="text-xs text-sultaf-muted mt-0.5">{{ now()->translatedFormat('l, d F Y') }}</p>
            </div>
        </header>

        <div class="flex-1 p-8">
            {{ $slot }}
        </div>
    </main>
</body>
</html>
