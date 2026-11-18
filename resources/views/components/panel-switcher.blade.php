@php
    $user = auth()->user();
@endphp

{{--
    Owner yang sedang "mampir" ke panel lain (Admin/Kasir/Dapur) selalu melihat
    tombol untuk kembali ke Dashboard Owner miliknya.
--}}
@if ($user->isOwner() && ! request()->routeIs('owner.*'))
    <a href="{{ route('owner.dashboard') }}"
       class="flex items-center gap-2 bg-sultaf-maroon/10 text-sultaf-maroon text-xs font-semibold rounded-xl px-3 py-2.5 hover:bg-sultaf-maroon/20 transition-colors">
        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M11 18l-6-6 6-6"/></svg>
        {{ __('Back to Owner Dashboard') }}
    </a>

{{--
    Admin yang sedang "mampir" ke panel lain (Kasir/Dapur, bukan Admin) selalu
    melihat tombol untuk kembali ke Panel Admin miliknya.
--}}
@elseif ($user->isAdmin() && ! request()->routeIs('admin.*'))
    <a href="{{ route('admin.dashboard') }}"
       class="flex items-center gap-2 bg-sultaf-maroon/10 text-sultaf-maroon text-xs font-semibold rounded-xl px-3 py-2.5 hover:bg-sultaf-maroon/20 transition-colors">
        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M11 18l-6-6 6-6"/></svg>
        {{ __('Back to Admin Panel') }}
    </a>
@endif
