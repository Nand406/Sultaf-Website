<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Sultaf' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-sultaf-pattern flex flex-col">

    {{-- ===================== TOP HEADER (STICKY) ===================== --}}
    <div class="sticky top-0 z-50 bg-sultaf-cream/95 backdrop-blur-md border-b border-sultaf-border/40 shadow-sm">
        <div class="w-full max-w-md lg:max-w-none mx-auto px-5 lg:px-10 xl:px-16 py-4">
            <div class="flex items-center justify-between">

                {{-- LOGO --}}
                <a href="{{ route('menu.index') }}" class="flex items-center gap-2 lg:gap-3">
                    <div class="w-9 h-9 lg:w-11 lg:h-11 rounded-xl bg-sultaf-maroon flex items-center justify-center">
                        <svg viewBox="0 0 24 24" class="w-4 h-4 lg:w-5 lg:h-5 text-sultaf-gold-light" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round">
                            <path d="M5 3v6a2 2 0 0 0 2 2v10M5 3v6M9 3v6M7 11V3"/>
                            <path d="M19 3c-2.5 1-3.5 4-2 7l1 2-3 9M19 3l-3 9"/>
                        </svg>
                    </div>
                    <span class="font-serif text-xl lg:text-2xl font-semibold text-sultaf-maroon">Sultaf</span>
                </a>

                {{-- NAV ITEMS (desktop) --}}
                <div class="hidden lg:flex items-center gap-1">
                    @include('components.nav-items')
                </div>

                {{-- ACTIONS --}}
                <div class="flex items-center gap-2">
                    @include('components.lang-switch')

                    @auth
                        {{-- PROFIL --}}
                        <a href="{{ route('profile.show') }}" title="{{ __('My Profile') }}"
                           class="w-8 h-8 rounded-full overflow-hidden bg-sultaf-maroon/10 border border-sultaf-border flex items-center justify-center text-sultaf-maroon text-xs font-bold shrink-0">
                            {{ strtoupper(substr(auth()->user()->username, 0, 1)) }}
                        </a>

                        {{-- LOGIKA CEK NOTIFIKASI BELUM DIBACA (BERDASARKAN ID) --}}
                        @php
                            // Ambil ID terakhir yang dibaca dari cache (default 0)
                            $lastReadId = \Illuminate\Support\Facades\Cache::get('notif_last_id_' . auth()->user()->id_user, 0);
                            
                            // Hitung notifikasi yang ID-nya lebih besar dari ID terakhir baca
                            $unreadCount = \App\Models\Notifikasi::where('id_user', auth()->user()->id_user)
                                ->where('id_notifikasi', '>', $lastReadId)
                                ->count();
                        @endphp

                        {{-- NOTIFIKASI --}}
                        <a href="{{ route('notifications.index') }}"
                           class="relative w-8 h-8 rounded-full bg-white border border-sultaf-border flex items-center justify-center text-sultaf-muted hover:text-sultaf-maroon transition-colors">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/>
                                <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                            </svg>
                            
                            {{-- BADGE ANGKA MERAH (INLINE CSS) --}}
                            @if($unreadCount > 0)
                                <span class="absolute flex items-center justify-center text-white font-bold border-2 border-white shadow-sm"
                                      style="top: -6px; right: -6px; background-color: #ef4444; min-width: 18px; height: 18px; font-size: 10px; border-radius: 9999px; padding: 0 4px; z-index: 10;">
                                    {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                                </span>
                            @endif
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="w-8 h-8 rounded-full bg-white border border-sultaf-border flex items-center justify-center text-sultaf-muted hover:text-sultaf-maroon transition-colors">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <circle cx="12" cy="8" r="4"/><path d="M4 21c1.5-4 5-6 8-6s6.5 2 8 6"/>
                            </svg>
                        </a>
                    @endauth

                    {{-- CART --}}
                    <a href="{{ route('cart.index') }}"
                       class="relative w-8 h-8 rounded-full bg-white border border-sultaf-border flex items-center justify-center text-sultaf-muted hover:text-sultaf-maroon transition-colors">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="9" cy="21" r="1"/><circle cx="19" cy="21" r="1"/>
                            <path d="M1 1h4l2.4 12.4a2 2 0 0 0 2 1.6h8.6a2 2 0 0 0 2-1.6L23 6H6"/>
                        </svg>
                        @if (session('cart') && count(session('cart')))
                            {{-- BADGE KERANJANG --}}
                            <span class="absolute flex items-center justify-center text-white font-bold border-2 border-white shadow-sm"
                                  style="top: -6px; right: -6px; background-color: #7A1620; min-width: 18px; height: 18px; font-size: 10px; border-radius: 9999px; padding: 0 4px; z-index: 10;">
                                {{ count(session('cart')) }}
                            </span>
                        @endif
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- ===================== KONTEN UTAMA ===================== --}}
    <div class="flex-1 w-full max-w-md lg:max-w-none mx-auto pb-24 lg:pb-10 px-0 lg:px-10 xl:px-16 relative">
        {{ $slot }}
    </div>

    {{-- ===================== BOTTOM NAV (mobile) ===================== --}}
    <nav class="lg:hidden fixed bottom-0 left-1/2 -translate-x-1/2 w-full max-w-md bg-white border-t border-sultaf-border
                flex items-stretch justify-around px-2 py-2 z-40">
        @include('components.nav-items')
    </nav>
</body>
</html>