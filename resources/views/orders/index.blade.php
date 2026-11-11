<x-layouts.app title="{{ __('Order History') }} - Sultaf">
    <div class="px-5 lg:px-10 xl:px-16 pt-6 max-w-lg lg:max-w-none mx-auto lg:mx-0">
        <div class="flex items-center justify-between mb-5">
            <h1 class="font-serif text-2xl font-bold text-sultaf-maroon">{{ __('Order History') }}</h1>
            @include('components.lang-switch')
        </div>

        <div class="space-y-4 max-w-lg">
            @forelse ($orders as $order)
                <a href="{{ route('orders.show', $order) }}" class="block bg-white rounded-2xl p-4 shadow-sm">
                    <div class="flex justify-between items-center mb-1">
                        {{-- DIUBAH: kode_transaksi -> id_transaksi --}}
                        <span class="font-semibold text-sultaf-ink">
                            #SLT-{{ str_pad($order->id_transaksi, 5, '0', STR_PAD_LEFT) }}
                        </span>
                        <span class="text-xs font-semibold uppercase px-2.5 py-1 rounded-full
                                     {{ match($order->status_pesanan) {
                                         'pending' => 'bg-amber-100 text-amber-700',
                                         'cooking' => 'bg-orange-100 text-orange-700',
                                         'ready' => 'bg-blue-100 text-blue-700',
                                         'diantar' => 'bg-indigo-100 text-indigo-700',
                                         'selesai' => 'bg-emerald-100 text-emerald-700',
                                         default => 'bg-sultaf-cream-dark text-sultaf-muted',
                                     } }}">
                            {{ __(ucfirst($order->status_pesanan)) }}
                        </span>
                    </div>
                    
                    {{-- DIUBAH: tgl_transaksi -> created_at --}}
                    <p class="text-sm text-sultaf-muted">
                        {{ $order->created_at ? $order->created_at->format('d M Y, H:i') : '-' }}
                    </p>
                    
                    {{-- DIUBAH: total_harga -> total_bayar --}}
                    <p class="font-serif text-lg font-bold text-sultaf-maroon mt-1">
                        Rp {{ number_format($order->total_bayar, 0, ',', '.') }}
                    </p>
                </a>
            @empty
                <p class="text-center text-sultaf-muted py-16">{{ __('No order history yet.') }}</p>
            @endforelse
        </div>
    </div>
</x-layouts.app>