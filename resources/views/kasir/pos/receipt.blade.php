<x-layouts.kasir title="Struk - Kasir Sultaf" pageTitle="Transaksi Berhasil">

    <style>
        @media print {
            body * { visibility: hidden; }
            #receiptPrintArea, #receiptPrintArea * { visibility: visible; }
            #receiptPrintArea {
                position: absolute; left: 0; top: 0; width: 100%; margin: 0; padding: 0;
            }
        }
    </style>

    <div class="max-w-md mx-auto">

        @if (session('success'))
            <div class="mb-6 bg-sultaf-success-soft border border-sultaf-success/20 text-sultaf-success text-sm rounded-xl px-4 py-3 text-center">
                ✓ {{ session('success') }}
            </div>
        @endif

        @php
            // Menghitung subtotal dan pajak secara manual karena kolomnya tidak ada di tabel transaksi
            $subtotal = $transaksi->detail_transaksi->sum('subtotal');
            $pajak = $subtotal * 0.10;
            $diskon = $transaksi->diskon ?? 0;
        @endphp

        <div id="receiptPrintArea" class="staff-card p-6">
            <div class="text-center mb-5">
                <div class="w-14 h-14 rounded-2xl bg-sultaf-maroon flex items-center justify-center mx-auto mb-3">
                    <svg class="w-7 h-7 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"><path d="M5 13l4 4L19 7"/></svg>
                </div>
                <p class="font-serif text-xl font-bold text-sultaf-ink">Sultaf Restaurant</p>
                <p class="text-xs text-sultaf-muted">Struk Pembayaran</p>
            </div>

            @if ($transaksi->user)
                <div class="bg-sultaf-success-soft border border-sultaf-success/20 rounded-xl px-4 py-3 mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-sultaf-success shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="5"/><path d="M8.5 13 7 21l5-2.5L17 21l-1.5-8"/></svg>
                    <p class="text-xs text-sultaf-success">
                        Member: <strong>{{ $transaksi->user->username }}</strong> — Total poin sekarang: <strong>{{ $transaksi->user->member->total_poin ?? 0 }}</strong>
                    </p>
                </div>
            @endif

            <div class="border-t border-b border-dashed border-sultaf-border py-4 mb-4 text-sm space-y-2">
                <div class="flex justify-between">
                    <span class="text-sultaf-muted">No. Transaksi</span>
                    <span class="font-semibold text-sultaf-ink">#SLT-{{ str_pad($transaksi->id_transaksi, 5, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sultaf-muted">Tanggal</span>
                    <span class="text-sultaf-ink">{{ $transaksi->created_at->format('d M Y H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sultaf-muted">Kasir</span>
                    <span class="text-sultaf-ink">{{ auth()->user()->username }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sultaf-muted">Tipe Pesanan</span>
                    <span class="capitalize text-sultaf-ink font-semibold">
                        @if($transaksi->tipe_pesanan === 'dine_in')
                            Dine In (Meja {{ $transaksi->no_meja ?? '-' }})
                        @else
                            Takeaway
                        @endif
                    </span>
                </div>
            </div>

            {{-- RINCIAN ITEM --}}
            <div class="space-y-2 mb-4 text-sm">
                @foreach ($transaksi->detail_transaksi as $item)
                    <div class="flex justify-between text-sultaf-ink">
                        <span>{{ $item->jumlah }}x {{ $item->menu->nama_menu ?? 'Menu Dihapus' }}</span>
                        <span>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                    </div>
                @endforeach
            </div>

            {{-- TOTAL --}}
            <div class="border-t border-dashed border-sultaf-border pt-4 space-y-1.5 text-sm">
                <div class="flex justify-between text-sultaf-muted">
                    <span>Subtotal</span>
                    <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sultaf-muted">
                    <span>Tax (10%)</span>
                    <span>Rp {{ number_format($pajak, 0, ',', '.') }}</span>
                </div>
                
                @if ($diskon > 0)
                    <div class="flex justify-between text-sultaf-success">
                        <span>Diskon</span>
                        <span>-Rp {{ number_format($diskon, 0, ',', '.') }}</span>
                    </div>
                @endif

                <div class="flex justify-between items-center pt-2 border-t border-sultaf-border">
                    <span class="font-semibold text-sultaf-ink">Total</span>
                    <span class="font-serif text-xl font-bold text-sultaf-maroon">Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}</span>
                </div>
                
                {{-- BLOK UANG DITERIMA & KEMBALIAN (Hanya muncul jika uang_bayar tidak null/kosong) --}}
                @if($transaksi->uang_bayar)
                    <div class="flex justify-between text-sultaf-ink pt-2 border-t border-sultaf-border mt-2">
                        <span class="font-medium">Uang Diterima</span>
                        <span class="font-semibold">Rp {{ number_format($transaksi->uang_bayar, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sultaf-success font-bold">
                        <span>Kembalian</span>
                        <span>Rp {{ number_format($transaksi->uang_bayar - $transaksi->total_bayar, 0, ',', '.') }}</span>
                    </div>
                @endif
                
                <div class="flex justify-between text-sultaf-muted text-xs pt-1 border-t border-sultaf-border mt-2">
                    <span>Metode Bayar</span>
                    <span class="uppercase">{{ $transaksi->metode_pembayaran }}</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-3 mt-5">
            <button onclick="window.print()" class="border border-sultaf-border rounded-xl py-3 text-sm font-semibold text-sultaf-ink hover:bg-white flex items-center justify-center gap-2">
                🖨️ Cetak
            </button>
            {{-- TOMBOL DIUBAH: Menjadi Kembali ke halaman Verifikasi --}}
            <a href="{{ route('kasir.pos') }}" class="bg-sultaf-maroon hover:bg-sultaf-maroon-dark text-white rounded-xl py-3 text-sm font-semibold text-center transition-colors">
                Kembali
            </a>
        </div>
    </div>
</x-layouts.kasir>