<x-layouts.app title="{{ __('Order Details') }} - Sultaf">

    <div class="px-5 lg:px-10 xl:px-16 pt-6">

        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <a href="{{ route('cart.index') }}" class="text-sultaf-ink">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M11 18l-6-6 6-6"/></svg>
                </a>
                <h1 class="font-serif text-2xl font-bold text-sultaf-maroon">{{ __('Checkout') }}</h1>
            </div>
            @include('components.lang-switch')
        </div>

        <x-checkout-steps :current="2" />

        @if ($errors->any())
            <div class="mb-4 bg-red-50 rounded-xl px-4 py-3 text-sm text-red-600 space-y-1">
                @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
            </div>
        @endif

        <div class="flex flex-col lg:flex-row gap-8 items-start">

            <div class="flex-1 min-w-0">
                <form method="POST" action="{{ route('checkout.details.store') }}">
                    @csrf

                    <h2 class="font-serif text-xl font-bold text-sultaf-ink mb-4">{{ __('Order Logistics') }}</h2>

                    <div class="grid grid-cols-2 gap-3 mb-6">
                        <label class="cursor-pointer">
                            <input type="radio" name="tipe_pesanan" value="dine_in" class="peer hidden"
                                   {{ old('tipe_pesanan', 'dine_in') === 'dine_in' ? 'checked' : '' }}
                                   onchange="document.getElementById('tableField').classList.remove('hidden')">
                            <div class="text-center py-3.5 rounded-xl border border-sultaf-border font-semibold text-sultaf-ink
                                        peer-checked:bg-sultaf-maroon peer-checked:text-white peer-checked:border-sultaf-maroon transition-colors">
                                🪑 {{ __('Dine-In') }}
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="tipe_pesanan" value="takeaway" class="peer hidden"
                                   {{ old('tipe_pesanan') === 'takeaway' ? 'checked' : '' }}
                                   onchange="document.getElementById('tableField').classList.add('hidden')">
                            <div class="text-center py-3.5 rounded-xl border border-sultaf-border font-semibold text-sultaf-ink
                                        peer-checked:bg-sultaf-maroon peer-checked:text-white peer-checked:border-sultaf-maroon transition-colors">
                                🥡 {{ __('Takeaway') }}
                            </div>
                        </label>
                    </div>

                    <div id="tableField" class="mb-6">
                        <label class="block text-sm font-semibold text-sultaf-ink mb-1.5">
                            {{ __('Table Number') }}
                        </label>
                        <select name="nomor_meja" class="input-field appearance-none cursor-pointer">
                            <option value="" disabled selected>-- {{ __('Choose table number') }} --</option>
                            @for ($n = 1; $n <= 20; $n++)
                                <option value="{{ $n }}"
                                        {{ old('nomor_meja') == $n ? 'selected' : '' }}>
                                    {{ __('Table') }} {{ $n }}
                                </option>
                            @endfor
                        </select>
                        <div class="mt-3 bg-sultaf-cream rounded-xl p-3 text-sm text-sultaf-ink flex gap-2 border border-sultaf-border">
                            <span class="shrink-0">ⓘ</span>
                            <span>{{ __('Table numbers are located on the plaque at the corner of your seating area.') }}</span>
                        </div>
                    </div>

                    <div class="mb-8">
                        <label class="block text-sm font-semibold text-sultaf-ink mb-2">{{ __('Special Instructions') }}</label>
                        <textarea name="catatan" rows="4"
                                  placeholder="{{ __('Any allergies or dietary preferences? Let our chef know...') }}"
                                  class="input-field">{{ old('catatan') }}</textarea>
                    </div>

                    <button type="submit" class="btn-primary flex items-center justify-center gap-2">
                        {{ __('Proceed to Payment') }}
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                    </button>
                </form>
            </div>

            <div class="hidden lg:block w-80 xl:w-96 shrink-0 sticky top-24">
                <div class="rounded-2xl bg-sultaf-maroon-dark overflow-hidden relative h-80">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-black/10"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-6">
                        <p class="font-serif text-2xl text-white font-semibold leading-snug">
                            {{ __('Prepared with heritage, served with heart.') }}
                        </p>
                        <p class="text-white/70 text-sm mt-2">Sultaf Yogyakarta Main Branch</p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-4 mt-4">
                    <p class="text-xs text-sultaf-muted uppercase tracking-wide mb-3">{{ __('Preparation Timing') }}</p>
                    <div class="grid grid-cols-2 gap-2">
                        <div class="border border-sultaf-maroon bg-sultaf-maroon/5 rounded-xl p-3 text-center">
                            <p class="text-sultaf-maroon text-lg">⚡</p>
                            <p class="text-xs font-semibold text-sultaf-maroon mt-1">{{ __('ASAP') }}</p>
                        </div>
                        <div class="border border-sultaf-border rounded-xl p-3 text-center text-sultaf-muted">
                            <p class="text-lg">🕐</p>
                            <p class="text-xs font-semibold mt-1">{{ __('Schedule') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
