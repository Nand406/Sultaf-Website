<x-layouts.dapur title="Kitchen Display - Dapur Sultaf" pageTitle="Kitchen Display">

    @if (session('success'))
        <div class="mb-6 bg-sultaf-success-soft border border-sultaf-success/20 text-sultaf-success text-sm rounded-xl px-4 py-3">
            ✓ {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mb-6 bg-sultaf-danger-soft border border-sultaf-danger/20 text-sultaf-danger text-sm rounded-xl px-4 py-3">
            {{ session('error') }}
        </div>
    @endif

    @php
        $columns = [
            'pending' => [
                'label' => 'Pending', 'actionable' => true, 'next' => 'cooking', 'nextLabel' => 'Mulai Masak',
                'bgSoft' => 'bg-amber-50/60', 'border' => 'border-amber-100', 'dot' => 'bg-amber-500',
                'btn' => 'bg-sultaf-clay hover:bg-sultaf-clay-dark',
            ],
            'cooking' => [
                'label' => 'Cooking', 'actionable' => true, 'next' => 'ready', 'nextLabel' => 'Selesai Masak',
                'bgSoft' => 'bg-orange-50/60', 'border' => 'border-orange-100', 'dot' => 'bg-orange-500',
                'btn' => 'bg-sultaf-clay hover:bg-sultaf-clay-dark',
            ],
            'ready' => [
                'label' => 'Ready', 'actionable' => false, 'note' => 'Menunggu diantar kasir',
                'bgSoft' => 'bg-emerald-50/60', 'border' => 'border-emerald-100', 'dot' => 'bg-emerald-500',
            ],
        ];
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        @foreach ($columns as $key => $col)
            <div class="{{ $col['bgSoft'] }} rounded-2xl border {{ $col['border'] }} min-h-[300px]">
                <div class="flex items-center justify-between px-4 py-3 border-b {{ $col['border'] }}">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full {{ $col['dot'] }}"></span>
                        <span class="font-semibold text-sultaf-ink">{{ $col['label'] }}</span>
                        @if (! $col['actionable'])
                            <span class="text-[10px] font-semibold uppercase text-sultaf-muted bg-white rounded-full px-2 py-0.5">Lihat saja</span>
                        @endif
                    </div>
                    <span class="text-xs font-semibold bg-white rounded-full px-2.5 py-1 text-sultaf-muted">
                        {{ isset($pesanan[$key]) ? $pesanan[$key]->count() : 0 }} Orders
                    </span>
                </div>

                <div class="p-3 space-y-3">
                    @forelse ($pesanan[$key] ?? [] as $transaksi)
                        <div class="bg-white rounded-xl border border-sultaf-border/70 p-4 shadow-sm">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-serif text-lg font-bold text-sultaf-ink">#{{ substr($transaksi->kode_transaksi, -4) }}</span>
                                <span class="text-xs font-semibold px-2 py-1 rounded-full
                                    {{ $transaksi->tgl_transaksi->diffInMinutes(now()) > 20 ? 'bg-sultaf-danger-soft text-sultaf-danger' : 'bg-sultaf-cream text-sultaf-muted' }}">
                                    {{ $transaksi->tgl_transaksi->diffForHumans(null, true) }}
                                </span>
                            </div>

                            <p class="text-xs text-sultaf-muted mb-2">
                                {{ $transaksi->nomor_meja ? 'Table ' . $transaksi->nomor_meja : 'Takeaway' }}
                                @if ($transaksi->user) • {{ $transaksi->user->name }} @endif
                            </p>

                            <div class="space-y-1 mb-3">
                                @foreach ($transaksi->items as $item)
                                    <p class="text-sm text-sultaf-ink/80">{{ $item->qty }}x {{ $item->menu->nama_makanan }}</p>
                                @endforeach
                                @if ($transaksi->catatan)
                                    <p class="text-xs font-semibold text-sultaf-danger pt-1">* {{ $transaksi->catatan }}</p>
                                @endif
                            </div>

                            @if ($col['actionable'])
                                <form method="POST" action="{{ route('dapur.pesanan.update', $transaksi) }}">
                                    @csrf
                                    <input type="hidden" name="status" value="{{ $col['next'] }}">
                                    <button type="submit"
                                            class="w-full {{ $col['btn'] }} text-white text-xs font-semibold rounded-lg py-2.5 transition-colors">
                                        {{ $col['nextLabel'] }}
                                    </button>
                                </form>
                            @else
                                <div class="w-full bg-sultaf-cream text-sultaf-muted text-xs font-semibold rounded-lg py-2.5 text-center cursor-not-allowed">
                                    {{ $col['note'] }}
                                </div>
                            @endif
                        </div>
                    @empty
                        <p class="text-center text-sultaf-muted/50 text-sm py-10">Tidak ada pesanan</p>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>
</x-layouts.dapur>
