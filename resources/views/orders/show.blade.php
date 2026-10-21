<x-layouts.app title="{{ __('Track Order') }} - Sultaf">
    <div class="px-5 lg:px-0 pt-6 max-w-lg mx-auto">

        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <a href="{{ route('orders.index') }}" class="text-sultaf-ink">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M11 18l-6-6 6-6"/></svg>
                </a>
                <h1 class="font-serif text-2xl font-bold text-sultaf-maroon">{{ __('Order') }} #{{ $transaksi->kode_transaksi }}</h1>
            </div>
            @include('components.lang-switch')
        </div>

        @php
            $statusOrder = ['pending' => 1, 'cooking' => 2, 'ready' => 3, 'diantar' => 4, 'selesai' => 5];
            $currentStep = $statusOrder[$transaksi->status_pesanan] ?? 1;
            $timeline = [
                ['label' => __('Order Placed'),      'step' => 1],
                ['label' => __('Being Cooked'),      'step' => 2],
                ['label' => __('Finished Cooking'),  'step' => 3],
                ['label' => __('On the Way'),        'step' => 4],
                ['label' => __('Completed'),         'step' => 5],
            ];
        @endphp

        <div class="bg-white rounded-2xl p-5 mb-6">
            @foreach ($timeline as $point)
                <div class="flex items-start gap-3 {{ !$loop->last ? 'pb-6 relative' : '' }}">
                    @if (!$loop->last)
                        <div class="absolute left-[15px] top-8 bottom-0 w-px {{ $point['step'] < $currentStep ? 'bg-sultaf-maroon' : 'bg-sultaf-border' }}"></div>
                    @endif
                    <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 text-xs font-bold
                                {{ $point['step'] <= $currentStep ? 'bg-sultaf-maroon text-white' : 'bg-sultaf-cream-dark text-sultaf-muted' }}">
                        @if ($point['step'] < $currentStep)
                            ✓
                        @else
                            {{ $point['step'] }}
                        @endif
                    </div>
                    <div>
                        <p class="font-semibold {{ $point['step'] <= $currentStep ? 'text-sultaf-ink' : 'text-sultaf-muted' }}">{{ $point['label'] }}</p>
                        @if ($point['step'] === $currentStep)
                            <p class="text-sm text-sultaf-maroon">{{ __('In progress…') }}</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="bg-white rounded-2xl p-5">
            <h3 class="font-semibold text-sultaf-ink mb-3">{{ __('Order Summary') }}</h3>
            <div class="divide-y divide-sultaf-border">
                @foreach ($transaksi->items as $item)
                    <div class="flex justify-between py-2 text-sm">
                        <span>{{ $item->qty }}x {{ __($item->menu->nama_makanan) }}</span>
                        <span>Rp {{ number_format($item->harga_satuan * $item->qty, 0, ',', '.') }}</span>
                    </div>
                @endforeach
            </div>
            <div class="flex justify-between items-center pt-3 mt-2 border-t border-sultaf-border">
                <span class="font-medium text-sultaf-ink">{{ __('Total') }}</span>
                <span class="font-serif text-lg font-bold text-sultaf-maroon">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>
</x-layouts.app>
