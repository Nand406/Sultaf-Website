@php
    $navItems = [
        ['label' => __('Home'), 'route' => 'menu.index', 'icon' => 'home'],
        ['label' => __('Menu'), 'route' => 'menu.index', 'icon' => 'menu'],
        ['label' => __('Rewards'), 'route' => 'rewards.index', 'icon' => 'rewards'],
        ['label' => __('History'), 'route' => 'orders.index', 'icon' => 'history'],
    ];
@endphp

@foreach ($navItems as $item)
    @php $isActive = request()->routeIs($item['route']); @endphp
    <a href="{{ Route::has($item['route']) ? route($item['route']) : '#' }}"
       class="flex flex-col lg:flex-row items-center gap-1 lg:gap-2 px-3 py-1 lg:py-2 lg:px-4 lg:rounded-full text-[11px] lg:text-sm font-medium
              {{ $isActive ? 'text-sultaf-maroon lg:bg-sultaf-maroon/10' : 'text-sultaf-muted hover:text-sultaf-maroon' }}">

        @switch($item['icon'])
            @case('home')
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 11.5 12 4l9 7.5"/><path d="M5 10v9a1 1 0 0 0 1 1h4v-6h4v6h4a1 1 0 0 0 1-1v-9"/>
                </svg>
                @break
            @case('menu')
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round">
                    <path d="M5 3v6a2 2 0 0 0 2 2v10M5 3v6M9 3v6M7 11V3"/>
                    <path d="M19 3c-2.5 1-3.5 4-2 7l1 2-3 9M19 3l-3 9"/>
                </svg>
                @break
            @case('rewards')
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="8" r="5"/><path d="M8.5 13 7 21l5-2.5L17 21l-1.5-8"/>
                </svg>
                @break
            @case('history')
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="4" y="3" width="16" height="18" rx="2"/><path d="M8 7h8M8 11h8M8 15h5"/>
                </svg>
                @break
        @endswitch
        {{ $item['label'] }}
    </a>
@endforeach
