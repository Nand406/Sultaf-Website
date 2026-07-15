<x-layouts.owner title="{{ __('Sales & Financial Report') }} - Owner Sultaf" pageTitle="{{ __('Sales & Financial Report') }}">

    {{-- ===================== FILTER TANGGAL ===================== --}}
    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div class="flex flex-wrap gap-2">
            @foreach ([
                'hari-ini' => __('Today'),
                '7hari' => __('Last 7 Days'),
                '30hari' => __('Last 30 Days'),
                'bulan-ini' => __('This Month'),
                'tahun-ini' => __('This Year'),
            ] as $key => $label)
                <a href="{{ route('owner.laporan', ['preset' => $key]) }}"
                   class="text-xs font-semibold px-4 py-2 rounded-full transition-colors
                          {{ $preset === $key ? 'bg-sultaf-maroon text-white' : 'bg-white border border-sultaf-border text-sultaf-ink hover:bg-sultaf-cream' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <form method="GET" action="{{ route('owner.laporan') }}" class="flex items-center gap-2">
            <input type="hidden" name="preset" value="custom">
            <input type="date" name="from" value="{{ $from->format('Y-m-d') }}"
                   class="text-xs border border-sultaf-border rounded-lg px-3 py-2 bg-white focus:outline-none focus:border-sultaf-maroon">
            <span class="text-sultaf-muted text-xs">—</span>
            <input type="date" name="to" value="{{ $to->format('Y-m-d') }}"
                   class="text-xs border border-sultaf-border rounded-lg px-3 py-2 bg-white focus:outline-none focus:border-sultaf-maroon">
            <button type="submit" class="text-xs font-semibold bg-sultaf-maroon-dark hover:bg-sultaf-maroon text-white rounded-lg px-4 py-2 transition-colors">
                {{ __('Apply') }}
            </button>
        </form>
    </div>

    <p class="text-xs text-sultaf-muted mb-6">
        {{ __('Showing data from') }} <strong class="text-sultaf-ink">{{ $from->translatedFormat('d M Y') }}</strong>
        {{ __('to') }} <strong class="text-sultaf-ink">{{ $to->translatedFormat('d M Y') }}</strong>
    </p>

    {{-- ===================== RINGKASAN ===================== --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5 mb-6">
        <div class="staff-card p-5">
            <p class="staff-label mb-2">{{ __('Total Revenue') }}</p>
            <p class="font-serif text-2xl font-bold text-sultaf-ink">Rp {{ number_format($totalOmzet, 0, ',', '.') }}</p>
        </div>
        <div class="staff-card p-5">
            <p class="staff-label mb-2">{{ __('Total Profit') }}</p>
            <p class="font-serif text-2xl font-bold text-sultaf-success">Rp {{ number_format($totalKeuntungan, 0, ',', '.') }}</p>
            <p class="text-xs text-sultaf-muted mt-1">
                {{ $totalOmzet > 0 ? round($totalKeuntungan / $totalOmzet * 100) : 0 }}% {{ __('margin') }}
            </p>
        </div>
        <div class="staff-card p-5">
            <p class="staff-label mb-2">{{ __('Total Transactions') }}</p>
            <p class="font-serif text-2xl font-bold text-sultaf-ink">{{ number_format($totalTransaksi) }}</p>
        </div>
        <div class="staff-card p-5">
            <p class="staff-label mb-2">{{ __('Average Order Value') }}</p>
            <p class="font-serif text-2xl font-bold text-sultaf-ink">Rp {{ number_format($rataRataTransaksi, 0, ',', '.') }}</p>
        </div>
        <div class="staff-card p-5">
            <p class="staff-label mb-2">{{ __('Total Discounts Given') }}</p>
            <p class="font-serif text-2xl font-bold text-sultaf-danger">Rp {{ number_format($totalDiskon, 0, ',', '.') }}</p>
        </div>
    </div>

    {{-- ===================== GRAFIK OMZET & KEUNTUNGAN ===================== --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="staff-card p-5">
            <p class="staff-label mb-4">{{ __('Revenue Trend') }}</p>
            <canvas id="omzetChart" height="90"></canvas>
        </div>
        <div class="staff-card p-5">
            <p class="staff-label mb-4">{{ __('Profit Trend') }}</p>
            <canvas id="keuntunganChart" height="90"></canvas>
        </div>
    </div>

    {{-- ===================== TOP MENU + KATEGORI ===================== --}}
    <div class="grid grid-cols-1 lg:grid-cols-[1fr_320px] gap-6 mb-6">

        <div class="staff-card overflow-hidden">
            <div class="px-5 py-4 border-b border-sultaf-border">
                <h3 class="font-semibold text-sultaf-ink">{{ __('Best Selling Items') }}</h3>
            </div>
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-sultaf-muted text-xs uppercase bg-sultaf-cream/60">
                        <th class="px-5 py-3 font-medium">{{ __('Menu') }}</th>
                        <th class="px-5 py-3 font-medium text-right">{{ __('Qty Sold') }}</th>
                        <th class="px-5 py-3 font-medium text-right">{{ __('Revenue') }}</th>
                        <th class="px-5 py-3 font-medium text-right">{{ __('Profit') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-sultaf-border">
                    @forelse ($topMenu as $row)
                        <tr>
                            <td class="px-5 py-3 text-sultaf-ink">{{ __($row['menu']->nama_makanan) }}</td>
                            <td class="px-5 py-3 text-sultaf-muted text-right">{{ $row['qty'] }}</td>
                            <td class="px-5 py-3 text-sultaf-ink text-right font-medium">Rp {{ number_format($row['revenue'], 0, ',', '.') }}</td>
                            <td class="px-5 py-3 text-sultaf-success text-right font-medium">Rp {{ number_format($row['keuntungan'], 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-5 py-10 text-center text-sultaf-muted">{{ __('No sales data for this period.') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="staff-card p-5">
            <h3 class="font-semibold text-sultaf-ink mb-4">{{ __('Revenue by Category') }}</h3>
            @if ($kategoriBreakdown->isNotEmpty())
                <canvas id="kategoriChart" height="200"></canvas>
                <div class="space-y-2 mt-4">
                    @foreach ($kategoriBreakdown as $i => $row)
                        <div class="flex items-center justify-between text-xs">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full" style="background-color: {{ ['#7A1620','#BF8B3D','#C1613D','#8A7566','#4A0E14'][$i % 5] }}"></span>
                                <span class="text-sultaf-ink">{{ __($row['kategori']) }}</span>
                            </div>
                            <span class="font-medium text-sultaf-ink">Rp {{ number_format($row['revenue'], 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-sultaf-muted text-center py-10">{{ __('No sales data for this period.') }}</p>
            @endif
        </div>
    </div>

    {{-- ===================== METODE PEMBAYARAN + TIPE PESANAN ===================== --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <div class="staff-card overflow-hidden">
            <div class="px-5 py-4 border-b border-sultaf-border">
                <h3 class="font-semibold text-sultaf-ink">{{ __('Payment Method Breakdown') }}</h3>
            </div>
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-sultaf-muted text-xs uppercase bg-sultaf-cream/60">
                        <th class="px-5 py-3 font-medium">{{ __('Method') }}</th>
                        <th class="px-5 py-3 font-medium text-right">{{ __('Transactions') }}</th>
                        <th class="px-5 py-3 font-medium text-right">{{ __('Total') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-sultaf-border">
                    @forelse ($metodePembayaran as $row)
                        <tr>
                            <td class="px-5 py-3 text-sultaf-ink uppercase">{{ $row['metode'] }}</td>
                            <td class="px-5 py-3 text-sultaf-muted text-right">{{ $row['jumlah'] }}</td>
                            <td class="px-5 py-3 text-sultaf-ink text-right font-medium">Rp {{ number_format($row['total'], 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="px-5 py-10 text-center text-sultaf-muted">{{ __('No sales data for this period.') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="staff-card overflow-hidden">
            <div class="px-5 py-4 border-b border-sultaf-border">
                <h3 class="font-semibold text-sultaf-ink">{{ __('Order Type Breakdown') }}</h3>
            </div>
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-sultaf-muted text-xs uppercase bg-sultaf-cream/60">
                        <th class="px-5 py-3 font-medium">{{ __('Type') }}</th>
                        <th class="px-5 py-3 font-medium text-right">{{ __('Transactions') }}</th>
                        <th class="px-5 py-3 font-medium text-right">{{ __('Total') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-sultaf-border">
                    @forelse ($tipePesanan as $row)
                        <tr>
                            <td class="px-5 py-3 text-sultaf-ink">
                                {{ $row['tipe'] === 'dine_in' ? __('Dine-In') : ($row['tipe'] === 'takeaway' ? __('Takeaway') : ucfirst($row['tipe'])) }}
                            </td>
                            <td class="px-5 py-3 text-sultaf-muted text-right">{{ $row['jumlah'] }}</td>
                            <td class="px-5 py-3 text-sultaf-ink text-right font-medium">Rp {{ number_format($row['total'], 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="px-5 py-10 text-center text-sultaf-muted">{{ __('No sales data for this period.') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Chart.js di-hosting sendiri (bukan CDN) supaya tidak bergantung koneksi internet --}}
    <script src="{{ asset('vendor/chartjs/chart.umd.js') }}"></script>
    <script>
        new Chart(document.getElementById('omzetChart'), {
            type: 'line',
            data: {
                labels: @json($omzetHarian->pluck('date')),
                datasets: [{
                    data: @json($omzetHarian->pluck('total')),
                    borderColor: '#7A1620',
                    backgroundColor: 'rgba(122, 22, 32, 0.08)',
                    fill: true,
                    tension: 0.35,
                    pointRadius: 3,
                    pointBackgroundColor: '#7A1620',
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { ticks: { color: '#8A7566', font: { size: 11 } }, grid: { color: '#F0E4D3' } },
                    x: { grid: { display: false }, ticks: { color: '#8A7566', font: { size: 11 } } }
                }
            }
        });

        new Chart(document.getElementById('keuntunganChart'), {
            type: 'line',
            data: {
                labels: @json($keuntunganHarian->pluck('date')),
                datasets: [{
                    data: @json($keuntunganHarian->pluck('total')),
                    borderColor: '#4F7A52',
                    backgroundColor: 'rgba(79, 122, 82, 0.08)',
                    fill: true,
                    tension: 0.35,
                    pointRadius: 3,
                    pointBackgroundColor: '#4F7A52',
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { ticks: { color: '#8A7566', font: { size: 11 } }, grid: { color: '#F0E4D3' } },
                    x: { grid: { display: false }, ticks: { color: '#8A7566', font: { size: 11 } } }
                }
            }
        });

        @if ($kategoriBreakdown->isNotEmpty())
        new Chart(document.getElementById('kategoriChart'), {
            type: 'doughnut',
            data: {
                labels: @json($kategoriBreakdown->pluck('kategori')),
                datasets: [{
                    data: @json($kategoriBreakdown->pluck('revenue')),
                    backgroundColor: ['#7A1620','#BF8B3D','#C1613D','#8A7566','#4A0E14'],
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                cutout: '65%',
            }
        });
        @endif
    </script>
</x-layouts.owner>
