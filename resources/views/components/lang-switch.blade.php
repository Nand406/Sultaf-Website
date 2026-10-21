@php
    $nextLocale = app()->getLocale() === 'id' ? 'en' : 'id';
@endphp
<div class="flex items-center gap-2">
    {{-- Toggle Bahasa --}}
    <a href="{{ route('lang.switch', $nextLocale) }}"
       class="text-xs font-semibold border border-sultaf-border rounded-full px-3 py-1.5 hover:bg-sultaf-cream flex items-center gap-1 bg-white">
        <span class="{{ app()->getLocale() === 'id' ? 'text-sultaf-maroon' : 'text-sultaf-muted' }}">ID</span>
        <span class="text-sultaf-border">/</span>
        <span class="{{ app()->getLocale() === 'en' ? 'text-sultaf-maroon' : 'text-sultaf-muted' }}">EN</span>
    </a>

    {{-- Logout — tampil untuk semua role yang sedang login --}}
    @auth
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" title="{{ __('Logout') }}"
                    class="w-8 h-8 rounded-full bg-white border border-sultaf-border flex items-center justify-center text-sultaf-muted hover:text-red-500 transition-colors">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/>
                </svg>
            </button>
        </form>
    @endauth
</div>
