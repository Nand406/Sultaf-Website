<x-layouts.kasir title="Dashboard - Kasir Sultaf" pageTitle="Dashboard Kasir">

    @if (session('success'))
        <div class="mb-6 bg-sultaf-success-soft border border-sultaf-success/20 text-sultaf-success text-sm rounded-xl px-4 py-3">
            ✓ {{ session('success') }}
        </div>
    @endif

    {{-- ===================== HERO BAND — 3 statistik jadi satu pita ===================== --}}
    <div class="bg-sultaf-maroon-dark bg-sultaf-pattern-dark rounded-2xl p-6 lg:p-8 mb-6 text-white">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 divide-y sm:divide-y-0 sm:divide-x divide-white/10">
            <div>
                <p class="text-[11px] uppercase tracking-widest text-white/50 mb-1.5">Menunggu Verifikasi</p>
                <p class="font-serif text-3xl font-bold text-sultaf-gold-light">{{ $stats['menunggu'] }}</p>
                <a href="{{ route('kasir.pembayaran') }}" class="text-xs text-white/70 hover:text-white font-medium mt-1.5 inline-block">Verifikasi sekarang →</a>
            </div>
            <div class="pt-6 sm:pt-0 sm:pl-6">
                <p class="text-[11px] uppercase tracking-widest text-white/50 mb-1.5">Terverifikasi Hari Ini</p>
                <p class="font-serif text-3xl font-bold text-emerald-300">{{ $stats['terverifikasi_hari_ini'] }}</p>
            </div>
            <div class="pt-6 sm:pt-0 sm:pl-6">
                <p class="text-[11px] uppercase tracking-widest text-white/50 mb-1.5">Pesanan Aktif</p>
                <p class="font-serif text-3xl font-bold">{{ $stats['pesanan_aktif'] }}</p>
                <a href="{{ route('kasir.pesanan') }}" class="text-xs text-white/70 hover:text-white font-medium mt-1.5 inline-block">Lihat status →</a>
            </div>
        </div>
    </div>

    {{-- ===================== 2 KOLOM ===================== --}}
    <div class="grid grid-cols-1 lg:grid-cols-[1fr_300px] gap-6 items-start">

        {{-- Kiri: Recent Orders --}}
        <div class="staff-card overflow-hidden">
            <div class="px-5 py-4 border-b border-sultaf-border">
                <h3 class="font-semibold text-sultaf-ink">Transaksi Terbaru</h3>
            </div>
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-sultaf-muted text-xs uppercase bg-sultaf-cream/60">
                        <th class="px-5 py-3 font-medium">Order ID</th>
                        <th class="px-5 py-3 font-medium">Pelanggan</th>
                        <th class="px-5 py-3 font-medium">Total</th>
                        <th class="px-5 py-3 font-medium">Pembayaran</th>
                        <th class="px-5 py-3 font-medium">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-sultaf-border">
                    @forelse ($recentOrders as $order)
                        <tr>
                            <td class="px-5 py-3 font-medium text-sultaf-maroon">#{{ $order->kode_transaksi }}</td>
                            <td class="px-5 py-3 text-sultaf-ink">{{ $order->nama_pelanggan ?? $order->user->name ?? 'Walk-in' }}</td>
                            <td class="px-5 py-3 text-sultaf-ink">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                            <td class="px-5 py-3">
                                <span class="text-xs font-semibold px-2.5 py-1 rounded-full
                                    {{ match($order->status_pembayaran) {
                                        'terverifikasi' => 'bg-sultaf-success-soft text-sultaf-success',
                                        'ditolak' => 'bg-sultaf-danger-soft text-sultaf-danger',
                                        default => 'bg-sultaf-warning-soft text-sultaf-maroon-dark',
                                    } }}">
                                    {{ ucfirst($order->status_pembayaran) }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-sultaf-muted capitalize">{{ $order->status_pesanan }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-5 py-8 text-center text-sultaf-muted">Belum ada transaksi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Kanan: Quick Actions, sticky --}}
        <div class="space-y-4 lg:sticky lg:top-24">
            <a href="{{ route('kasir.pos') }}" class="staff-card block p-5 !bg-sultaf-maroon hover:!bg-sultaf-maroon-dark transition-colors group">
                <div class="w-11 h-11 rounded-xl bg-white/15 flex items-center justify-center mb-3">
                    <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg>
                </div>
                <p class="font-semibold text-white">Buka POS</p>
                <p class="text-xs text-white/70">Layani pembeli langsung</p>
            </a>

            <a href="{{ route('kasir.pembayaran') }}" class="staff-card block p-5 hover:border-sultaf-maroon/40 transition-colors">
                <div class="w-11 h-11 rounded-xl bg-sultaf-warning-soft flex items-center justify-center mb-3">
                    <svg class="w-5 h-5 text-sultaf-maroon-dark" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/></svg>
                </div>
                <p class="font-semibold text-sultaf-ink">Verifikasi Pembayaran</p>
                <p class="text-xs text-sultaf-muted">{{ $stats['menunggu'] }} menunggu konfirmasi</p>
            </a>

            <a href="{{ route('kasir.pesanan') }}" class="staff-card block p-5 hover:border-sultaf-maroon/40 transition-colors">
                <div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center mb-3">
                    <svg class="w-5 h-5 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="3" width="16" height="18" rx="2"/><path d="M8 7h8M8 11h8M8 15h5"/></svg>
                </div>
                <p class="font-semibold text-sultaf-ink">Status Pesanan</p>
                <p class="text-xs text-sultaf-muted">{{ $stats['pesanan_aktif'] }} sedang diproses</p>
            </a>
        </div>
    </div>
</x-layouts.kasir>
