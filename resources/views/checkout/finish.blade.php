<x-layouts.app title="{{ __('Order Confirmed') }} - Sultaf">

    <style>
        @media print {
            body * { visibility: hidden; }
            #receiptPrintArea, #receiptPrintArea * { visibility: visible; }
            #receiptPrintArea {
                position: absolute; left: 0; top: 0; width: 100%; margin: 0; padding: 0;
            }
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

                <div id="receiptPrintArea" class="bg-white rounded-2xl border border-sultaf-border/70 p-5">
                    <div class="flex justify-between items-start mb-5 pb-4 border-b border-sultaf-border">
                        <div>
                            <p class="text-xs text-sultaf-muted uppercase tracking-wide">{{ __('Order Number') }}</p>
                            <p class="font-serif text-xl font-bold text-sultaf-maroon mt-0.5">#{{ $transaksi->kode_transaksi }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-sultaf-muted uppercase tracking-wide">{{ __('Date & Time') }}</p>
                            <p class="text-sm font-medium text-sultaf-ink mt-0.5">{{ $transaksi->tgl_transaksi->format('M d, Y H:i') }}</p>
                        </div>
                    </div>

                    <div class="divide-y divide-sultaf-border">
                        @foreach ($transaksi->items as $item)
                            <div class="flex items-center justify-between py-3">
                                <div class="flex items-center gap-3">
                                    <span class="text-xs bg-sultaf-cream-dark font-semibold rounded-lg px-2 py-1 shrink-0">{{ $item->qty }}x</span>
                                    <div>
                                        <p class="font-medium text-sultaf-ink">{{ __($item->menu->nama_makanan) }}</p>
                                        <p class="text-xs text-sultaf-muted">{{ Str::limit(__($item->menu->deskripsi), 50) }}</p>
                                    </div>
                                </div>
                                <span class="font-medium text-sultaf-ink shrink-0 ml-2">
                                    Rp {{ number_format($item->harga_satuan * $item->qty, 0, ',', '.') }}
                                </span>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t border-sultaf-border pt-4 mt-2 space-y-2 text-sm">
                        <div class="flex justify-between text-sultaf-muted">
                            <span>{{ __('Subtotal') }}</span>
                            <span>Rp {{ number_format($transaksi->subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sultaf-muted">
                            <span>{{ __('Tax (10%)') }}</span>
                            <span>Rp {{ number_format($transaksi->pajak, 0, ',', '.') }}</span>
                        </div>
                        @if ($transaksi->diskon_member > 0)
                            @php $diskonPercent = $transaksi->subtotal > 0 ? round($transaksi->diskon_member / $transaksi->subtotal * 100) : 0; @endphp
                            <div class="flex justify-between text-sultaf-success">
                                <span>{{ __('Loyalty Discount') }} ({{ $diskonPercent }}%)</span>
                                <span>-Rp {{ number_format($transaksi->diskon_member, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between items-center pt-3 border-t border-sultaf-border">
                            <span class="font-semibold text-sultaf-ink text-base">{{ __('Grand Total') }}</span>
                            <span class="font-serif text-2xl font-bold text-sultaf-maroon">
                                Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>

                <button onclick="window.print()"
                        class="w-full mt-4 border border-sultaf-border bg-white text-sultaf-ink hover:bg-sultaf-cream rounded-xl py-3 text-sm font-semibold flex items-center justify-center gap-2 transition-colors">
                    🖨️ {{ __('Print Receipt') }}
                </button>
            </div>

            <div class="w-full lg:w-80 xl:w-96 shrink-0 lg:sticky lg:top-24 space-y-4">
                <a href="{{ route('orders.show', $transaksi) }}" class="btn-primary flex items-center justify-center gap-2">
                    🔍 {{ __('Track Order') }}
                </a>
                <a href="{{ route('menu.index') }}"
                   class="block text-center text-sm text-sultaf-muted font-medium py-3 border border-sultaf-border bg-white rounded-xl hover:bg-sultaf-cream transition-colors">
                    + {{ __('Order More') }}
                </a>

                <div class="bg-white rounded-2xl border border-sultaf-border/70 p-5">
                    <p class="text-xs text-sultaf-muted uppercase tracking-wide mb-3">{{ __('Payment Status') }}</p>
                    <div class="flex items-center gap-2">
                        @if ($transaksi->status_pembayaran === 'terverifikasi')
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

                @if ($transaksi->nomor_meja)
                    <div class="bg-white rounded-2xl border border-sultaf-border/70 p-5">
                        <p class="text-xs text-sultaf-muted uppercase tracking-wide mb-1">{{ __('Your Table') }}</p>
                        <p class="font-serif text-4xl font-bold text-sultaf-maroon">No. {{ $transaksi->nomor_meja }}</p>
                    </div>
                @endif

                <div class="bg-white rounded-2xl border border-sultaf-border/70 p-5">
                    <p class="text-xs text-sultaf-muted uppercase tracking-wide mb-2">{{ __('Order Type') }}</p>
                    <p class="font-semibold text-sultaf-ink capitalize">
                        {{ $transaksi->tipe_pesanan === 'dine_in' ? '🪑 '.__('Dine-In') : '🥡 '.__('Takeaway') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
