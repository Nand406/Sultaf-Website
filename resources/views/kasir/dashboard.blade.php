<x-layouts.kasir title="Dashboard - Kasir Sultaf" pageTitle="Dashboard Kasir">

    @if (session('success'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-xl px-4 py-3">
            ✓ {{ session('success') }}
        </div>
    @endif

    {{-- Stat cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-8">
        <div class="bg-white rounded-2xl p-5 border border-gray-100">
            <p class="text-xs text-gray-400 uppercase tracking-wide mb-2">Menunggu Verifikasi</p>
            <p class="font-serif text-3xl font-bold text-amber-600">{{ $stats['menunggu'] }}</p>
            <a href="{{ route('kasir.pembayaran') }}" class="text-xs text-sultaf-maroon font-medium mt-2 inline-block">Verifikasi sekarang →</a>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100">
            <p class="text-xs text-gray-400 uppercase tracking-wide mb-2">Terverifikasi Hari Ini</p>
            <p class="font-serif text-3xl font-bold text-emerald-600">{{ $stats['terverifikasi_hari_ini'] }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100">
            <p class="text-xs text-gray-400 uppercase tracking-wide mb-2">Pesanan Aktif</p>
            <p class="font-serif text-3xl font-bold text-sultaf-maroon">{{ $stats['pesanan_aktif'] }}</p>
            <a href="{{ route('kasir.pesanan') }}" class="text-xs text-sultaf-maroon font-medium mt-2 inline-block">Lihat status →</a>
        </div>
    </div>

    {{-- Quick actions --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <a href="{{ route('kasir.pos') }}" class="bg-sultaf-maroon hover:bg-sultaf-maroon-dark text-white rounded-2xl p-5 flex items-center gap-4 transition-colors">
            <div class="w-11 h-11 rounded-xl bg-white/15 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg>
            </div>
            <div>
                <p class="font-semibold">Buka POS</p>
                <p class="text-xs text-white/70">Layani pembeli langsung</p>
            </div>
        </a>
        <a href="{{ route('kasir.pembayaran') }}" class="bg-white border border-gray-200 rounded-2xl p-5 flex items-center gap-4 hover:border-sultaf-maroon transition-colors">
            <div class="w-11 h-11 rounded-xl bg-amber-50 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/></svg>
            </div>
            <div>
                <p class="font-semibold text-gray-800">Verifikasi Pembayaran</p>
                <p class="text-xs text-gray-400">{{ $stats['menunggu'] }} menunggu konfirmasi</p>
            </div>
        </a>
        <a href="{{ route('kasir.pesanan') }}" class="bg-white border border-gray-200 rounded-2xl p-5 flex items-center gap-4 hover:border-sultaf-maroon transition-colors">
            <div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="3" width="16" height="18" rx="2"/><path d="M8 7h8M8 11h8M8 15h5"/></svg>
            </div>
            <div>
                <p class="font-semibold text-gray-800">Status Pesanan</p>
                <p class="text-xs text-gray-400">{{ $stats['pesanan_aktif'] }} sedang diproses</p>
            </div>
        </a>
    </div>

    {{-- Recent orders --}}
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800">Transaksi Terbaru</h3>
        </div>
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-gray-400 text-xs uppercase">
                    <th class="px-5 py-3 font-medium">Order ID</th>
                    <th class="px-5 py-3 font-medium">Pelanggan</th>
                    <th class="px-5 py-3 font-medium">Total</th>
                    <th class="px-5 py-3 font-medium">Pembayaran</th>
                    <th class="px-5 py-3 font-medium">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($recentOrders as $order)
                    <tr>
                        <td class="px-5 py-3 font-medium text-sultaf-maroon">#{{ $order->kode_transaksi }}</td>
                        <td class="px-5 py-3 text-gray-700">{{ $order->user->name ?? 'Walk-in' }}</td>
                        <td class="px-5 py-3 text-gray-700">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                        <td class="px-5 py-3">
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full
                                {{ match($order->status_pembayaran) {
                                    'terverifikasi' => 'bg-emerald-50 text-emerald-700',
                                    'ditolak' => 'bg-red-50 text-red-700',
                                    default => 'bg-amber-50 text-amber-700',
                                } }}">
                                {{ ucfirst($order->status_pembayaran) }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-gray-600 capitalize">{{ $order->status_pesanan }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-5 py-8 text-center text-gray-400">Belum ada transaksi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.kasir>
