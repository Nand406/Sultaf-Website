<x-layouts.owner title="{{ __('Dashboard') }} - Owner Sultaf" pageTitle="{{ __('Admin Overview') }}">

    {{-- ===================== BARIS 1: STAT CARDS ===================== --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">

        {{-- Today's Revenue --}}
        <div class="staff-card p-5">
            <div class="flex items-center justify-between mb-2">
                <p class="staff-label">{{ __("Today's Revenue") }}</p>
                <div class="w-7 h-7 rounded-lg bg-sultaf-cream flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-sultaf-maroon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="2"/></svg>
                </div>
            </div>
            <p class="font-serif text-2xl font-bold text-sultaf-ink">Rp {{ number_format($revenueToday / 1000000, 1) }}M</p>
            <p class="text-xs mt-1.5 flex items-center gap-1 {{ $revenueGrowth >= 0 ? 'text-sultaf-success' : 'text-sultaf-danger' }}">
                <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    @if ($revenueGrowth >= 0)
                        <path d="M18 15l-6-6-6 6"/>
                    @else
                        <path d="M6 9l6 6 6-6"/>
                    @endif
                </svg>
                {{ abs($revenueGrowth) }}% {{ __('from yesterday') }}
            </p>
        </div>

        {{-- Today's Profit --}}
        <div class="staff-card p-5">
            <div class="flex items-center justify-between mb-2">
                <p class="staff-label">{{ __("Today's Profit") }}</p>
                <div class="w-7 h-7 rounded-lg bg-sultaf-success-soft flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-sultaf-success" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                </div>
            </div>
            <p class="font-serif text-2xl font-bold text-sultaf-success">Rp {{ number_format($profitToday / 1000000, 1) }}M</p>
            <p class="text-xs mt-1.5 text-sultaf-muted">
                {{ $revenueToday > 0 ? round($profitToday / $revenueToday * 100) : 0 }}% {{ __('margin') }}
            </p>
        </div>

        {{-- Customers Served --}}
        <div class="staff-card p-5">
            <div class="flex items-center justify-between mb-2">
                <p class="staff-label">{{ __('Customers Served') }}</p>
                <div class="w-7 h-7 rounded-lg bg-sultaf-cream flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-sultaf-maroon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="7" r="4"/><path d="M2 21v-2a4 4 0 0 1 4-4h6a4 4 0 0 1 4 4v2"/><path d="M17 3.5a4 4 0 0 1 0 7.5M23 21v-2a4 4 0 0 0-3-3.87"/></svg>
                </div>
            </div>
            <p class="font-serif text-2xl font-bold text-sultaf-ink">{{ $customersServed }}</p>
            <p class="text-xs mt-1.5 text-sultaf-muted">{{ __('Peak hour') }}: {{ $peakHourLabel }}</p>
        </div>

        {{-- Average Rating --}}
        <div class="staff-card p-5">
            <div class="flex items-center justify-between mb-2">
                <p class="staff-label">{{ __('Average Rating') }}</p>
                <svg class="w-4 h-4 text-sultaf-gold" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l2.9 6.3 6.9.6-5.2 4.6 1.6 6.8L12 16.9l-6.2 3.4 1.6-6.8L2.2 8.9l6.9-.6L12 2z"/></svg>
            </div>
            <p class="font-serif text-2xl font-bold text-sultaf-ink">{{ $averageRating }}/5.0</p>
            <p class="text-xs mt-1.5 text-sultaf-muted">{{ $totalMenuRated }} {{ __('menu items rated') }}</p>
        </div>
    </div>

    {{-- ===================== BARIS 2: RECENT ORDERS + KITCHEN LOAD ===================== --}}
    <div class="grid grid-cols-1 lg:grid-cols-[1fr_320px] gap-6 mb-6">

        <div class="staff-card overflow-hidden">
            <div class="px-5 py-4 border-b border-sultaf-border flex items-center justify-between">
                <h3 class="font-semibold text-sultaf-ink">{{ __('Recent Orders') }}</h3>
                <a href="{{ route('kasir.pesanan') }}" class="text-xs text-sultaf-maroon font-medium">{{ __('View All') }} →</a>
            </div>
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-sultaf-muted text-xs uppercase bg-sultaf-cream/60">
                        <th class="px-5 py-3 font-medium">{{ __('Order ID') }}</th>
                        <th class="px-5 py-3 font-medium">{{ __('Customer') }}</th>
                        <th class="px-5 py-3 font-medium">{{ __('Table') }}</th>
                        <th class="px-5 py-3 font-medium">{{ __('Total') }}</th>
                        <th class="px-5 py-3 font-medium">{{ __('Status') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-sultaf-border">
                    @forelse ($recentOrders as $order)
                        <tr>
                            <td class="px-5 py-3 font-medium text-sultaf-maroon">#{{ $order->kode_transaksi }}</td>
                            <td class="px-5 py-3 text-sultaf-ink">{{ $order->nama_pelanggan ?? $order->user->name ?? 'Walk-in' }}</td>
                            <td class="px-5 py-3 text-sultaf-muted">{{ $order->nomor_meja ? 'T-'.str_pad($order->nomor_meja, 2, '0', STR_PAD_LEFT) : '-' }}</td>
                            <td class="px-5 py-3 text-sultaf-ink">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                            <td class="px-5 py-3">
                                <span class="text-xs font-semibold px-2.5 py-1 rounded-full
                                    {{ match($order->status_pesanan) {
                                        'pending' => 'bg-sultaf-warning-soft text-sultaf-maroon-dark',
                                        'cooking' => 'bg-orange-100 text-orange-700',
                                        'ready' => 'bg-blue-100 text-blue-700',
                                        'diantar' => 'bg-indigo-100 text-indigo-700',
                                        'selesai' => 'bg-sultaf-success-soft text-sultaf-success',
                                        default => 'bg-sultaf-cream text-sultaf-muted',
                                    } }}">
                                    {{ __(ucfirst($order->status_pesanan)) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-5 py-10 text-center text-sultaf-muted">{{ __('No orders yet.') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Kitchen Load --}}
        <div class="staff-card p-5 h-fit">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-sultaf-ink">{{ __('Kitchen Load') }}</h3>
                <span class="text-xs font-semibold px-2.5 py-1 rounded-full
                    {{ $mainKitchenLoad >= 80 ? 'bg-sultaf-danger-soft text-sultaf-danger' : ($mainKitchenLoad >= 50 ? 'bg-sultaf-warning-soft text-sultaf-maroon-dark' : 'bg-sultaf-success-soft text-sultaf-success') }}">
                    {{ $mainKitchenLoad >= 80 ? __('High') : ($mainKitchenLoad >= 50 ? __('Medium') : __('Low')) }}
                </span>
            </div>

            <div class="mb-3">
                <div class="flex justify-between text-xs text-sultaf-muted mb-1">
                    <span>{{ __('Main Kitchen') }}</span><span class="font-semibold text-sultaf-ink">{{ $mainKitchenLoad }}%</span>
                </div>
                <div class="h-2 bg-sultaf-cream rounded-full overflow-hidden">
                    <div class="h-full bg-sultaf-clay rounded-full" style="width: {{ $mainKitchenLoad }}%"></div>
                </div>
            </div>

            <div class="flex justify-between items-center pt-3 border-t border-sultaf-border mt-3">
                <div>
                    <p class="font-serif text-lg font-bold text-sultaf-ink">{{ $activeTickets }}</p>
                    <p class="text-[11px] text-sultaf-muted uppercase">{{ __('Active Tickets') }}</p>
                </div>
                <div class="text-right">
                    <p class="font-serif text-lg font-bold text-sultaf-ink">{{ $avgPrepMinutes }}m</p>
                    <p class="text-[11px] text-sultaf-muted uppercase">{{ __('Avg. Prep Time') }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ===================== BARIS 3: PRIORITY ORDER + REVENUE & PROFIT CHART ===================== --}}
    <div class="grid grid-cols-1 lg:grid-cols-[320px_1fr] gap-6">

        <div class="staff-card overflow-hidden">
            <div class="bg-sultaf-maroon-dark text-white px-5 py-3">
                <p class="text-[11px] uppercase tracking-wide text-white/70">{{ __('Priority Order Spotlight') }}</p>
            </div>
            @if ($priorityOrder)
                <div class="p-5">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <p class="text-xs text-sultaf-muted">{{ __('Ticket') }} #{{ $priorityOrder->kode_transaksi }}</p>
                            <p class="font-serif text-lg font-bold text-sultaf-ink">
                                {{ $priorityOrder->nomor_meja ? __('Table') . ' ' . $priorityOrder->nomor_meja : __('Takeaway') }}
                            </p>
                        </div>
                        <span class="text-xs text-sultaf-muted">{{ $priorityOrder->tgl_transaksi->diffForHumans() }}</span>
                    </div>

                    <div class="space-y-1.5 mb-4 pb-4 border-b border-dashed border-sultaf-border">
                        @foreach ($priorityOrder->items as $item)
                            <div class="flex justify-between text-sm">
                                <span class="text-sultaf-ink/80">{{ $item->qty }}x {{ __($item->menu->nama_makanan) }}</span>
                                <span class="text-sultaf-muted">Rp {{ number_format($item->qty * $item->harga_satuan, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                        @if ($priorityOrder->catatan)
                            <p class="text-xs font-semibold text-sultaf-danger pt-1">* {{ $priorityOrder->catatan }}</p>
                        @endif
                    </div>

                    <div class="flex justify-between items-center mb-4">
                        <span class="text-sm font-semibold text-sultaf-ink">{{ __('Total') }}</span>
                        <span class="font-serif text-lg font-bold text-sultaf-maroon">Rp {{ number_format($priorityOrder->total_harga, 0, ',', '.') }}</span>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <button onclick="window.print()" class="bg-sultaf-maroon hover:bg-sultaf-maroon-dark text-white text-xs font-semibold rounded-lg py-2.5 transition-colors">
                            {{ __('Print') }}
                        </button>
                        <a href="{{ route('kasir.pesanan') }}" class="text-center border border-sultaf-border text-sultaf-muted hover:bg-sultaf-cream text-xs font-semibold rounded-lg py-2.5 transition-colors">
                            {{ __('View') }}
                        </a>
                    </div>
                </div>
            @else
                <div class="p-10 text-center text-sultaf-muted text-sm">{{ __('No urgent orders right now.') }}</div>
            @endif
        </div>

        <div class="staff-card p-5">
            <div class="flex items-center justify-between mb-4">
                <p class="staff-label">{{ __('Revenue & Profit Momentum (Today)') }}</p>
                <div class="flex items-center gap-3 text-[11px]">
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-sm bg-sultaf-cream-dark"></span>{{ __('Revenue') }}</span>
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-sultaf-success"></span>{{ __('Profit') }}</span>
                </div>
            </div>
            <canvas id="revenueChart" height="90"></canvas>
        </div>
    </div>

    {{-- Chart.js di-hosting sendiri (bukan CDN) supaya tidak bergantung koneksi internet --}}
    <script src="{{ asset('vendor/chartjs/chart.umd.js') }}"></script>
    <script>
        const ctx = document.getElementById('revenueChart');
        const labels = @json(collect($revenueByHour)->pluck('label'));
        const revenueValues = @json(collect($revenueByHour)->pluck('value'));
        const profitValues = @json(collect($profitByHour)->pluck('value'));

        new Chart(ctx, {
            data: {
                labels: labels,
                datasets: [
                    {
                        type: 'bar',
                        label: 'Revenue',
                        data: revenueValues,
                        backgroundColor: '#F0E4D3',
                        borderRadius: 6,
                        maxBarThickness: 32,
                        order: 2,
                    },
                    {
                        type: 'line',
                        label: 'Profit',
                        data: profitValues,
                        borderColor: '#4F7A52',
                        backgroundColor: '#4F7A52',
                        tension: 0.35,
                        pointRadius: 3,
                        pointBackgroundColor: '#4F7A52',
                        order: 1,
                    },
                ]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { display: false },
                    x: { grid: { display: false }, ticks: { color: '#8A7566', font: { size: 11 } } }
                }
            }
        });
    </script>
</x-layouts.owner>
