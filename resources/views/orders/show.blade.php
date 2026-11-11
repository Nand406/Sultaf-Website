<x-layouts.app title="{{ __('Order Details') }} - Sultaf">
    <div class="px-5 lg:px-10 xl:px-16 pt-6 max-w-2xl mx-auto">
        
        {{-- Header --}}
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('orders.index') }}" class="w-8 h-8 flex items-center justify-center rounded-full border border-sultaf-border text-sultaf-ink hover:bg-white">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M11 18l-6-6 6-6"/></svg>
            </a>
            <h1 class="font-serif text-2xl font-bold text-sultaf-maroon">{{ __('Order Details') }}</h1>
        </div>

        {{-- Info Utama --}}
        <div class="bg-sultaf-maroon-dark bg-sultaf-pattern-dark rounded-2xl p-6 text-white mb-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-[11px] uppercase tracking-widest text-white/50 mb-1">{{ __('Order Number') }}</p>
                    <p class="font-serif text-xl font-bold">#SLT-{{ str_pad($transaksi->id_transaksi, 5, '0', STR_PAD_LEFT) }}</p>
                </div>
                <div class="text-right">
                    <p class="text-[11px] uppercase tracking-widest text-white/50 mb-1">{{ __('Date') }}</p>
                    <p class="text-sm">{{ $transaksi->created_at->format('d M Y, H:i') }}</p>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4 pt-4 border-t border-white/10">
                <div>
                    <p class="text-[11px] uppercase tracking-widest text-white/50 mb-1">{{ __('Status') }}</p>
                    <span class="inline-block text-xs font-semibold uppercase px-2.5 py-1 rounded-full bg-white/20">
                        {{ __(ucfirst($transaksi->status_pesanan)) }}
                    </span>
                </div>
                <div>
                    <p class="text-[11px] uppercase tracking-widest text-white/50 mb-1">{{ __('Order Type') }}</p>
                    <p class="text-sm">
                        @if($transaksi->tipe_pesanan === 'dine_in')
                            🪑 Dine In (Meja {{ $transaksi->no_meja ?? '-' }})
                        @else
                            🥡 Takeaway
                        @endif
                    </p>
                </div>
            </div>
        </div>

        {{-- Daftar Menu --}}
        <div class="bg-white rounded-2xl p-5 border border-sultaf-border mb-6">
            <h3 class="font-semibold text-sultaf-ink mb-4">{{ __('Order Summary') }}</h3>
            <div class="divide-y divide-sultaf-border">
                {{-- DIUBAH: items -> detail_transaksi --}}
                @foreach ($transaksi->detail_transaksi as $item)
                    <div class="flex justify-between py-3 text-sm">
                        <div>
                            {{-- DIUBAH: qty -> jumlah, nama_makanan -> nama_menu --}}
                            <p class="font-semibold text-sultaf-ink">{{ $item->jumlah }}x {{ $item->menu->nama_menu ?? 'Menu Dihapus' }}</p>
                            <p class="text-xs text-sultaf-muted mt-0.5">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</p>
                        </div>
                        <span class="font-semibold text-sultaf-ink">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                    </div>
                @endforeach
            </div>

            @php
                $subtotal = $transaksi->detail_transaksi->sum('subtotal');
                $pajak = $subtotal * 0.10;
            @endphp

            {{-- Rincian Pembayaran --}}
            <div class="border-t border-sultaf-border mt-2 pt-4 space-y-2 text-sm">
                <div class="flex justify-between text-sultaf-muted">
                    <span>{{ __('Subtotal') }}</span>
                    <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sultaf-muted">
                    <span>{{ __('Tax (10%)') }}</span>
                    <span>Rp {{ number_format($pajak, 0, ',', '.') }}</span>
                </div>
                @if ($transaksi->diskon > 0)
                    <div class="flex justify-between text-sultaf-success font-semibold">
                        <span>Diskon</span>
                        <span>-Rp {{ number_format($transaksi->diskon, 0, ',', '.') }}</span>
                    </div>
                @endif
                <div class="flex justify-between items-center pt-3 border-t border-sultaf-border">
                    <span class="font-bold text-sultaf-ink text-base">{{ __('Grand Total') }}</span>
                    {{-- DIUBAH: total_harga -> total_bayar --}}
                    <span class="font-serif text-xl font-bold text-sultaf-maroon">Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        {{-- Status Pembayaran --}}
        <div class="bg-white rounded-2xl p-5 border border-sultaf-border mb-6">
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
</x-layouts.app>