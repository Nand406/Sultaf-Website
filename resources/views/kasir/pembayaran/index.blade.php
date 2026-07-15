<x-layouts.kasir title="Verifikasi Pembayaran - Kasir" pageTitle="Order Verification">

    @if (session('success'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-xl px-4 py-3">
            ✓ {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-2xl p-4 border border-gray-100">
            <p class="text-xs text-gray-400 uppercase">Waiting</p>
            <p class="font-serif text-2xl font-bold text-amber-600">{{ str_pad($menunggu->count(), 2, '0', STR_PAD_LEFT) }}</p>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-gray-100">
            <p class="text-xs text-gray-400 uppercase">Total Value</p>
            <p class="font-serif text-2xl font-bold text-gray-800">Rp {{ number_format($menunggu->sum('total_harga'), 0, ',', '.') }}</p>
        </div>
        <div class="bg-emerald-50 rounded-2xl p-4 border border-emerald-100 flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
            <span class="text-sm font-semibold text-emerald-700">{{ $terverifikasiHariIni }} verified today</span>
        </div>
    </div>

    <div class="space-y-4">
        @forelse ($menunggu as $transaksi)
            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                <div class="p-5 flex flex-col lg:flex-row lg:items-start gap-5">

                    <div class="w-full lg:w-40 shrink-0">
                        @if ($transaksi->bukti_pembayaran)
                            <a href="{{ asset('storage/'.$transaksi->bukti_pembayaran) }}" target="_blank"
                               class="block rounded-xl overflow-hidden border border-gray-200 h-32 lg:h-40">
                                <img src="{{ asset('storage/'.$transaksi->bukti_pembayaran) }}"
                                     class="w-full h-full object-cover" alt="Bukti pembayaran">
                            </a>
                            <p class="text-[11px] text-center text-gray-400 mt-1">Klik untuk perbesar</p>
                        @else
                            <div class="rounded-xl border border-dashed border-gray-200 h-32 lg:h-40 flex items-center justify-center text-gray-300 text-xs text-center px-2">
                                Tanpa bukti<br>(Cash / QRIS di tempat)
                            </div>
                        @endif
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-2 mb-2">
                            <h3 class="font-semibold text-gray-800">
                                {{ $transaksi->nama_pelanggan ?? $transaksi->user->name ?? 'Pelanggan' }}
                            </h3>
                            @if ($transaksi->no_telepon)
                                <span class="text-xs font-medium text-gray-400">{{ $transaksi->no_telepon }}</span>
                            @endif
                            @if ($transaksi->nomor_meja)
                                <span class="text-xs font-semibold bg-sultaf-maroon/10 text-sultaf-maroon px-2 py-0.5 rounded-full">
                                    Table {{ str_pad($transaksi->nomor_meja, 2, '0', STR_PAD_LEFT) }}
                                </span>
                            @else
                                <span class="text-xs font-semibold bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">Takeaway</span>
                            @endif
                            <span class="text-xs font-semibold uppercase bg-blue-50 text-blue-700 px-2 py-0.5 rounded-full">
                                {{ $transaksi->metode_pembayaran }}
                            </span>
                        </div>

                        <p class="text-sm text-gray-500 mb-3">
                            Order:
                            {{ $transaksi->items->map(fn ($i) => "{$i->qty}x {$i->menu->nama_makanan}")->join(', ') }}
                        </p>

                        <div class="flex items-center gap-4 text-sm">
                            <span class="font-serif text-xl font-bold text-sultaf-maroon">
                                Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                            </span>
                            <span class="text-gray-400">•</span>
                            <span class="text-gray-400">{{ $transaksi->tgl_transaksi->diffForHumans() }}</span>
                            <span class="text-gray-400">•</span>
                            <span class="text-gray-400">#{{ $transaksi->kode_transaksi }}</span>
                        </div>
                    </div>

                    <div class="flex lg:flex-col gap-2 shrink-0 w-full lg:w-44">
                        <form method="POST" action="{{ route('kasir.pembayaran.verify', $transaksi) }}" class="flex-1 lg:flex-none">
                            @csrf
                            <button type="submit"
                                    class="w-full bg-sultaf-maroon hover:bg-sultaf-maroon-dark text-white text-sm font-semibold rounded-xl py-2.5 flex items-center justify-center gap-1.5 transition-colors">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 13l4 4L19 7"/></svg>
                                Verify &amp; Send
                            </button>
                        </form>

                        <button type="button" onclick="document.getElementById('reject-{{ $transaksi->id }}').classList.toggle('hidden')"
                                class="flex-1 lg:flex-none border border-gray-200 text-gray-600 hover:bg-gray-50 text-sm font-semibold rounded-xl py-2.5 transition-colors">
                            Reject
                        </button>
                    </div>
                </div>

                <div id="reject-{{ $transaksi->id }}" class="hidden border-t border-gray-100 bg-gray-50 p-4">
                    <form method="POST" action="{{ route('kasir.pembayaran.reject', $transaksi) }}" class="flex gap-2">
                        @csrf
                        <input type="text" name="alasan" placeholder="Alasan penolakan (opsional)"
                               class="flex-1 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-sultaf-maroon">
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-lg px-4">
                            Konfirmasi Tolak
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl border border-gray-100 p-16 text-center text-gray-400">
                <p class="text-4xl mb-3">✓</p>
                <p>Tidak ada pembayaran yang menunggu verifikasi.</p>
            </div>
        @endforelse
    </div>
</x-layouts.kasir>
