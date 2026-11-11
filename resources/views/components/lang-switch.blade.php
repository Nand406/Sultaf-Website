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
</div>