<x-layouts.app title="{{ __('Order Confirmed') }} - Sultaf">

    <style>
        @media print {
            body * { visibility: hidden; }
            #receiptPrintArea, #receiptPrintArea * { visibility: visible; }
            #receiptPrintArea { position: absolute; left: 0; top: 0; width: 100%; margin: 0; padding: 0; }
        }
    </style>

    <div class="px-5 lg:px-0 pt-6 pb-6">
        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('menu.index') }}" class="w-8 h-8 flex items-center justify-center rounded-full border border-sultaf-border text-sultaf-ink hover:bg-white">✕</a>
            <h1 class="font-serif text-2xl font-bold text-sultaf-maroon">{{ __('Order Confirmed') }}</h1>
            @include('components.lang-switch')
        </div>

        <div class="flex flex-col lg:flex-row gap-8 items-start">
            <div class="flex-1 min-w-0">
                <div class="flex flex-col items-center text-center mb-8">
                    <div class="w-16 h-16 rounded-2xl bg-sultaf-maroon flex items-center justify-center mb-4 shadow-lg shadow-sultaf-maroon/20">
                        <svg class="w-8 h-8 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"><path d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <h2 class="font-serif text-2xl font-bold text-sultaf-ink">{{ __('Order Successful') }}</h2>
                    <p class="text-sultaf-muted mt-2 max-w-sm">{{ __('Thank you for choosing Sultaf. Your meal is being prepared with care.') }}</p>
                </div>

                @php
                    $subtotal = $transaksi->detail_transaksi->sum('subtotal');
                    $pajak = $subtotal * 0.10;
                    $pointsEarned = floor($transaksi->total_bayar / 1000);
                    $diskon = $transaksi->diskon ?? 0;
                @endphp

                <div id="receiptPrintArea" class="bg-white rounded-2xl border border-sultaf-border/70 p-5">
                    <div class="flex justify-between items-start mb-5 pb-4 border-b border-sultaf-border">
                        <div>
                            <p class="text-xs text-sultaf-muted uppercase tracking-wide">{{ __('Order Number') }}</p>
                            <p class="font-serif text-xl font-bold text-sultaf-maroon mt-0.5">
                                #SLT-{{ str_pad($transaksi->id_transaksi, 5, '0', STR_PAD_LEFT) }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-sultaf-muted uppercase tracking-wide">{{ __('Date & Time') }}</p>
                            <p class="text-sm font-medium text-sultaf-ink mt-0.5">
                                {{ $transaksi->created_at ? $transaksi->created_at->format('M d, Y H:i') : '-' }}
                            </p>
                        </div>
                    </div>

                    {{-- Tipe Pesanan & Meja --}}
                    <div class="flex justify-between items-center mb-5 pb-4 border-b border-sultaf-border text-sm">
                        <span class="text-sultaf-muted">Tipe Pesanan</span>
                        <span class="font-semibold text-sultaf-ink">
                            @if($transaksi->tipe_pesanan === 'dine_in')
                                🪑 Dine In 
                                (Meja {{ $transaksi->no_meja ?: 'Belum dipilih' }})
                            @else
                                🥡 Takeaway
                            @endif
                        </span>
                    </div>

                    <div class="divide-y divide-sultaf-border">
                        @foreach ($transaksi->detail_transaksi as $item)
                            <div class="flex items-center justify-between py-3">
                                <div>
                                    <p class="font-semibold">{{ $item->menu->nama_menu ?? 'Menu Tidak Ditemukan' }}</p>
                                    <p class="text-sm text-gray-500">Jumlah: {{ $item->jumlah }}</p>
                                </div>
                                <div class="text-right">
                                    <p>Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</p>
                                    <p class="text-sm font-bold">Subtotal: Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t border-sultaf-border pt-4 mt-2 space-y-2 text-sm">
                        <div class="flex justify-between text-sultaf-muted">
                            <span>{{ __('Subtotal') }}</span>
                            <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sultaf-muted">
                            <span>{{ __('Tax (10%)') }}</span>
                            <span>Rp {{ number_format($pajak, 0, ',', '.') }}</span>
                        </div>
                        
                        <div class="flex justify-between text-sultaf-success font-semibold {{ $diskon > 0 ? '' : 'opacity-50' }}">
                            <span>Potongan Harga</span>
                            <span>-Rp {{ number_format($diskon, 0, ',', '.') }}</span>
                        </div>

                        <div class="flex justify-between items-center pt-3 border-t border-sultaf-border">
                            <span class="font-semibold text-sultaf-ink text-base">{{ __('Grand Total') }}</span>
                            <span class="font-serif text-2xl font-bold text-sultaf-maroon">
                                Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="flex justify-between items-center pt-2 text-sultaf-maroon font-semibold">
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                Poin Didapat
                            </span>
                            <span>+{{ number_format($pointsEarned) }} Poin</span>
                        </div>
                    </div>
                </div>

                <button onclick="window.print()" class="w-full mt-4 border border-sultaf-border bg-white text-sultaf-ink hover:bg-sultaf-cream rounded-xl py-3 text-sm font-semibold flex items-center justify-center gap-2 transition-colors">
                    🖨️ {{ __('Print Receipt') }}
                </button>
            </div>

            <div class="w-full lg:w-80 xl:w-96 shrink-0 lg:sticky lg:top-24 space-y-4">
                <a href="{{ route('orders.show', $transaksi) }}" class="btn-primary flex items-center justify-center gap-2">
                    🔍 {{ __('Track Order') }}
                </a>
                <a href="{{ route('menu.index') }}" class="block text-center text-sm text-sultaf-muted font-medium py-3 border border-sultaf-border bg-white rounded-xl hover:bg-sultaf-cream transition-colors">
                    + {{ __('Order More') }}
                </a>

                <div class="bg-white rounded-2xl border border-sultaf-border/70 p-5">
                    <p class="text-xs text-sultaf-muted uppercase tracking-wide mb-3">{{ __('Payment Status') }}</p>
                    <div class="flex items-center gap-2">
                        @if ($transaksi->status_pembayaran === 'terverifikasi' || $transaksi->status_pembayaran === 'lunas')
                            <span class="w-2.5 h-2.5 rounded-full bg-sultaf-success"></span>
                            <span class="font-semibold text-sultaf-success text-sm">{{ __('Verified') }}</span>
                        @elseif ($transaksi->status_pembayaran === 'ditolak')
                            <span class="w-2.5 h-2.5 rounded-full bg-sultaf-danger"></span>
                            <span class="font-semibold text-sultaf-danger text-sm">{{ __('Rejected') }}</span>
                        @else
                            <span class="w-2.5 h-2.5 rounded-full bg-sultaf-gold animate-pulse"></span>
                            <span class="font-semibold text-sultaf-gold text-sm">{{ __('Awaiting Verification') }}</span>
                        @endif
                    </div>
                    <p class="text-xs text-sultaf-muted mt-2">
                        {{ __('Method') }}: <strong class="text-sultaf-ink">{{ strtoupper($transaksi->metode_pembayaran ?? '-') }}</strong>
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>