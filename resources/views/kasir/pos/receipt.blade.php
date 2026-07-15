<x-layouts.kasir title="Struk - Kasir Sultaf" pageTitle="Transaksi Berhasil">

    {{-- CSS khusus print: sembunyikan semua elemen halaman KECUALI #receiptPrintArea --}}
    <style>
        @media print {
            body * { visibility: hidden; }
            #receiptPrintArea, #receiptPrintArea * { visibility: visible; }
            #receiptPrintArea {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                margin: 0;
                padding: 0;
            }
        }
    </style>

    <div class="max-w-md mx-auto">

        @if (session('success'))
            <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-xl px-4 py-3 text-center">
                ✓ {{ session('success') }}
            </div>
        @endif

        {{-- Hanya isi div ini yang akan tercetak --}}
        <div id="receiptPrintArea" class="bg-white rounded-2xl border border-gray-100 p-6">
            <div class="text-center mb-5">
                <div class="w-14 h-14 rounded-2xl bg-sultaf-maroon flex items-center justify-center mx-auto mb-3">
                    <svg class="w-7 h-7 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"><path d="M5 13l4 4L19 7"/></svg>
                </div>
                <p class="font-serif text-xl font-bold text-gray-800">Sultaf Restaurant</p>
                <p class="text-xs text-gray-400">Struk Pembayaran</p>
            </div>

            @if ($transaksi->user)
                <div class="bg-emerald-50 border border-emerald-100 rounded-xl px-4 py-3 mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="5"/><path d="M8.5 13 7 21l5-2.5L17 21l-1.5-8"/></svg>
                    <p class="text-xs text-emerald-700">
                        Member: <strong>{{ $transaksi->user->name }}</strong> — Total poin sekarang: <strong>{{ $transaksi->user->points }}</strong>
                    </p>
                </div>
            @elseif ($transaksi->no_telepon)
                <div class="bg-gray-50 border border-gray-100 rounded-xl px-4 py-3 mb-4">
                    <p class="text-xs text-gray-500">Nomor HP {{ $transaksi->no_telepon }} tidak terdaftar sebagai member.</p>
                </div>
            @endif

            <div class="border-t border-b border-dashed border-gray-200 py-4 mb-4 text-sm space-y-1">
                <div class="flex justify-between"><span class="text-gray-400">No. Transaksi</span><span class="font-semibold">{{ $transaksi->kode_transaksi }}</span></div>
                <div class="flex justify-between"><span class="text-gray-400">Tanggal</span><span>{{ $transaksi->tgl_transaksi->format('d/m/Y H:i') }}</span></div>
                <div class="flex justify-between"><span class="text-gray-400">Kasir</span><span>{{ auth()->user()->name }}</span></div>
                <div class="flex justify-between"><span class="text-gray-400">Tipe</span><span class="capitalize">{{ str_replace('_', ' ', $transaksi->tipe_pesanan) }}</span></div>
                @if ($transaksi->nomor_meja)
                    <div class="flex justify-between"><span class="text-gray-400">Meja</span><span>{{ $transaksi->nomor_meja }}</span></div>
                @endif
            </div>

            <div class="space-y-2 mb-4 text-sm">
                @foreach ($transaksi->items as $item)
                    <div class="flex justify-between">
                        <span>{{ $item->qty }}x {{ $item->menu->nama_makanan }}</span>
                        <span>Rp {{ number_format($item->qty * $item->harga_satuan, 0, ',', '.') }}</span>
                    </div>
                @endforeach
            </div>

            <div class="border-t border-dashed border-gray-200 pt-4 space-y-1.5 text-sm">
                <div class="flex justify-between text-gray-500"><span>Subtotal</span><span>Rp {{ number_format($transaksi->subtotal, 0, ',', '.') }}</span></div>
                <div class="flex justify-between text-gray-500"><span>Tax (10%)</span><span>Rp {{ number_format($transaksi->pajak, 0, ',', '.') }}</span></div>
                <div class="flex justify-between items-center pt-2 border-t border-gray-100">
                    <span class="font-semibold text-gray-800">Total</span>
                    <span class="font-serif text-xl font-bold text-sultaf-maroon">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-gray-400 text-xs pt-1">
                    <span>Metode Bayar</span><span class="uppercase">{{ $transaksi->metode_pembayaran }}</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-3 mt-5">
            <button onclick="window.print()" class="border border-gray-200 rounded-xl py-3 text-sm font-semibold text-gray-600 hover:bg-gray-50">
                🖨️ Cetak
            </button>
            <a href="{{ route('kasir.pos') }}" class="bg-sultaf-maroon hover:bg-sultaf-maroon-dark text-white rounded-xl py-3 text-sm font-semibold text-center">
                Transaksi Baru
            </a>
        </div>
    </div>
</x-layouts.kasir>
