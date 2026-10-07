<x-layouts.kasir title="Verifikasi Pembayaran - Kasir" pageTitle="Order Verification">

    @if (session('success'))
        <div class="mb-6 bg-sultaf-success-soft border border-sultaf-success/20 text-sultaf-success text-sm rounded-xl px-4 py-3">
            ✓ {{ session('success') }}
        </div>
    @endif

    {{-- ===================== HERO BAND ===================== --}}
    <div class="bg-sultaf-maroon-dark bg-sultaf-pattern-dark rounded-2xl p-6 lg:p-8 mb-6 text-white">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 divide-y sm:divide-y-0 sm:divide-x divide-white/10">
            <div>
                <p class="text-[11px] uppercase tracking-widest text-white/50 mb-1.5">Waiting</p>
                <p class="font-serif text-3xl font-bold text-sultaf-gold-light">{{ str_pad($menunggu->count(), 2, '0', STR_PAD_LEFT) }}</p>
            </div>
            <div class="pt-6 sm:pt-0 sm:pl-6">
                <p class="text-[11px] uppercase tracking-widest text-white/50 mb-1.5">Total Value</p>
                {{-- DIUBAH: 'total_harga' menjadi 'total_bayar' --}}
                <p class="font-serif text-3xl font-bold">Rp {{ number_format($menunggu->sum('total_bayar'), 0, ',', '.') }}</p>
            </div>
            <div class="pt-6 sm:pt-0 sm:pl-6 flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-300"></span>
                <span class="text-sm font-semibold text-emerald-300">{{ $terverifikasiHariIni }} verified today</span>
            </div>
        </div>
    </div>

    {{-- ===================== DAFTAR TRANSAKSI MENUNGGU ===================== --}}
    <div class="space-y-4">
        @forelse ($menunggu as $transaksi)
            <div class="staff-card overflow-hidden">
                <div class="p-5 flex flex-col lg:flex-row lg:items-start gap-5">

                    <div class="w-full lg:w-40 shrink-0">
                        @if ($transaksi->bukti_pembayaran)
                            <a href="{{ asset('storage/'.$transaksi->bukti_pembayaran) }}" target="_blank"
                               class="block rounded-xl overflow-hidden border border-sultaf-border h-32 lg:h-40">
                                <img src="{{ asset('storage/'.$transaksi->bukti_pembayaran) }}"
                                     class="w-full h-full object-cover" alt="Bukti pembayaran">
                            </a>
                            <p class="text-[11px] text-center text-sultaf-muted mt-1">Klik untuk perbesar</p>
                        @else
                            <div class="rounded-xl border border-dashed border-sultaf-border h-32 lg:h-40 flex items-center justify-center text-sultaf-muted text-xs text-center px-2 bg-sultaf-cream/40">
                                Tanpa bukti<br>(Cash / QRIS di tempat)
                            </div>
                        @endif
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-2 mb-2">
                            <h3 class="font-semibold text-sultaf-ink">
                                {{-- DIUBAH: 'name' menjadi 'username' --}}
                                {{ $transaksi->nama_pelanggan ?? $transaksi->user->username ?? 'Pelanggan' }}
                            </h3>
                            @if ($transaksi->no_telepon)
                                <span class="text-xs font-medium text-sultaf-muted">{{ $transaksi->no_telepon }}</span>
                            @endif
                            {{-- DIUBAH: 'nomor_meja' menjadi 'no_meja' --}}
                            @if ($transaksi->no_meja)
                                <span class="text-xs font-semibold bg-sultaf-maroon/10 text-sultaf-maroon px-2 py-0.5 rounded-full">
                                    Table {{ str_pad($transaksi->no_meja, 2, '0', STR_PAD_LEFT) }}
                                </span>
                            @else
                                <span class="text-xs font-semibold bg-sultaf-cream text-sultaf-muted px-2 py-0.5 rounded-full">Takeaway</span>
                            @endif
                            <span class="text-xs font-semibold uppercase bg-blue-50 text-blue-700 px-2 py-0.5 rounded-full">
                                {{ $transaksi->metode_pembayaran }}
                            </span>
                        </div>

                        <p class="text-sm text-sultaf-muted mb-3">
                            Order:
                            {{ $transaksi->detail_transaksi->map(fn ($i) => "{$i->jumlah}x {$i->menu->nama_menu}")->join(', ') }}
                        </p>

                        <div class="flex items-center gap-4 text-sm">
                            <span class="font-serif text-xl font-bold text-sultaf-maroon">
                                Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}
                            </span>
                            <span class="text-sultaf-border">•</span>
                            <span class="text-sultaf-muted">{{ $transaksi->created_at->diffForHumans() }}</span>
                            <span class="text-sultaf-border">•</span>
                            <span class="text-sultaf-muted">#SLT-{{ str_pad($transaksi->id_transaksi, 5, '0', STR_PAD_LEFT) }}</span>
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

                        <button type="button" onclick="document.getElementById('reject-{{ $transaksi->id_transaksi }}').classList.toggle('hidden')"
                                class="flex-1 lg:flex-none border border-sultaf-border text-sultaf-muted hover:bg-sultaf-cream text-sm font-semibold rounded-xl py-2.5 transition-colors">
                            Reject
                        </button>
                    </div>
                </div>

                <div id="reject-{{ $transaksi->id_transaksi }}" class="hidden border-t border-sultaf-border bg-sultaf-cream/50 p-4">
                    <form method="POST" action="{{ route('kasir.pembayaran.reject', $transaksi) }}" class="flex gap-2">
                        @csrf
                        <input type="text" name="alasan" placeholder="Alasan penolakan (opsional)"
                               class="flex-1 border border-sultaf-border rounded-lg px-3 py-2 text-sm bg-white focus:outline-none focus:border-sultaf-maroon">
                        <button type="submit" class="bg-sultaf-danger hover:opacity-90 text-white text-sm font-semibold rounded-lg px-4">
                            Konfirmasi Tolak
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="staff-card p-16 text-center text-sultaf-muted">
                <p class="text-4xl mb-3">✓</p>
                <p>Tidak ada pembayaran yang menunggu verifikasi.</p>
            </div>
        @endforelse
    </div>
</x-layouts.kasir>