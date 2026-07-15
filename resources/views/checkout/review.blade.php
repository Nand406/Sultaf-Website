<x-layouts.app title="{{ __('Review Order') }} - Sultaf">

    <div class="px-5 lg:px-10 xl:px-16 pt-6">

        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <a href="{{ route('menu.index') }}" class="text-sultaf-ink">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M11 18l-6-6 6-6"/></svg>
                </a>
                <h1 class="font-serif text-2xl font-bold text-sultaf-maroon">{{ __('Checkout') }}</h1>
            </div>
            @include('components.lang-switch')
        </div>

        <x-checkout-steps :current="1" />

        @if (session('error'))
            <div class="mb-4 text-sm text-red-600">{{ session('error') }}</div>
        @endif
        @if (session('success'))
            <div class="mb-4 text-sm text-emerald-600 bg-emerald-50 rounded-xl px-4 py-3">✓ {{ session('success') }}</div>
        @endif

        <div class="flex flex-col lg:flex-row gap-8 items-start">

            <div class="flex-1 min-w-0">
                <h2 class="font-serif text-xl font-bold text-sultaf-maroon mb-4">{{ __('Review your order') }}</h2>

                <div class="bg-white rounded-2xl divide-y divide-sultaf-border overflow-hidden">
                    @forelse ($items as $item)
                        <div class="flex items-center gap-4 p-4">
                            <div class="w-16 h-16 rounded-xl bg-sultaf-cream-dark overflow-hidden shrink-0">
                                @if ($item['menu']->foto_makanan)
                                    <img src="{{ asset('storage/'.$item['menu']->foto_makanan) }}" class="w-full h-full object-cover">
                                @endif
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-sultaf-ink">{{ __($item['menu']->nama_makanan) }}</p>
                                <div class="flex items-center gap-2 mt-2">
                                    <form method="POST" action="{{ route('cart.decrease', $item['menu']) }}">
                                        @csrf
                                        <button class="w-7 h-7 rounded-full border border-sultaf-border text-sultaf-ink hover:border-sultaf-maroon hover:text-sultaf-maroon">−</button>
                                    </form>
                                    <span class="text-sm font-semibold text-sultaf-ink w-6 text-center">{{ $item['qty'] }}</span>
                                    <form method="POST" action="{{ route('cart.add', $item['menu']) }}">
                                        @csrf
                                        <input type="hidden" name="qty" value="1">
                                        <button class="w-7 h-7 rounded-full border border-sultaf-maroon bg-sultaf-maroon text-white hover:bg-sultaf-maroon-dark">+</button>
                                    </form>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <p class="font-semibold text-sultaf-maroon">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</p>
                                <form method="POST" action="{{ route('cart.remove', $item['menu']) }}">
                                    @csrf @method('DELETE')
                                    <button class="text-xs text-sultaf-muted hover:text-red-500 mt-1">{{ __('Remove') }}</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="p-10 text-center text-sultaf-muted">{{ __('Your cart is empty.') }}</p>
                    @endforelse
                </div>

                @if ($items->isNotEmpty())
                    <div class="hidden lg:flex mt-6 rounded-2xl bg-sultaf-maroon-dark overflow-hidden items-end relative h-44">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        <p class="relative z-10 font-serif text-xl text-white font-semibold p-6 leading-snug">
                            {{ __('Prepared with heritage, served with heart.') }}
                        </p>
                    </div>
                @endif
            </div>

            @if ($items->isNotEmpty())
                <div class="w-full lg:w-80 xl:w-96 shrink-0 lg:sticky lg:top-24">
                    <div class="bg-white rounded-2xl p-5 space-y-3">
                        <p class="font-semibold text-sultaf-ink mb-1">{{ __('Order Summary') }}</p>
                        <div class="flex justify-between text-sm text-sultaf-muted">
                            <span>{{ __('Subtotal') }}</span><span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm text-sultaf-muted">
                            <span>{{ __('Tax (10%)') }}</span><span>Rp {{ number_format($tax, 0, ',', '.') }}</span>
                        </div>
                        <div class="border-t border-dashed border-sultaf-border pt-3 flex justify-between items-center">
                            <span class="font-serif text-lg text-sultaf-ink">{{ __('Total') }}</span>
                            <span class="font-serif text-2xl font-bold text-sultaf-maroon">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <a href="{{ route('checkout.details') }}" class="btn-primary inline-block text-center mt-4">
                        {{ __('Continue to Details') }}
                    </a>

                    <a href="{{ route('menu.index') }}" class="block text-center text-sm text-sultaf-muted font-medium mt-3 py-1 hover:text-sultaf-maroon">
                        + {{ __('Add more items') }}
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
