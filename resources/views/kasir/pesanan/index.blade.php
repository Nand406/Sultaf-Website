<x-layouts.kasir title="Status Pesanan - Kasir" pageTitle="Order Status Board">

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
        // Class ditulis literal (bukan digabung dinamis) supaya terbaca Tailwind JIT compiler
        $columns = [
            'pending' => [
                'label' => 'Pending', 'actionable' => false, 'note' => 'Menunggu dapur mulai memasak',
                'bgSoft' => 'bg-amber-50/60', 'border' => 'border-amber-100', 'dot' => 'bg-amber-500',
            ],
            'cooking' => [
                'label' => 'Cooking', 'actionable' => false, 'note' => 'Sedang dimasak dapur',
                'bgSoft' => 'bg-orange-50/60', 'border' => 'border-orange-100', 'dot' => 'bg-orange-500',
            ],
            'ready' => [
                'label' => 'Ready', 'actionable' => true, 'next' => 'diantar', 'nextLabel' => 'Antar Pesanan',
                'bgSoft' => 'bg-emerald-50/60', 'border' => 'border-emerald-100', 'dot' => 'bg-emerald-500',
                'btn' => 'bg-sultaf-success hover:opacity-90',
            ],
            'diantar' => [
                'label' => 'Diantar', 'actionable' => true, 'next' => 'selesai', 'nextLabel' => 'Selesaikan',
                'bgSoft' => 'bg-blue-50/60', 'border' => 'border-blue-100', 'dot' => 'bg-blue-500',
                'btn' => 'bg-blue-600 hover:bg-blue-700',
            ],
        ];
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
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
                                {{-- DIUBAH: Menggunakan generate ID dari id_transaksi --}}
                                <span class="font-serif text-lg font-bold text-sultaf-ink">
                                    #{{ substr(str_pad($transaksi->id_transaksi, 5, '0', STR_PAD_LEFT), -4) }}
                                </span>
                                {{-- DIUBAH: tgl_transaksi menjadi created_at --}}
                                <span class="text-xs font-semibold px-2 py-1 rounded-full bg-sultaf-cream text-sultaf-muted">
                                    {{ $transaksi->created_at->diffForHumans(null, true) }}
                                </span>
                            </div>

                            <p class="text-xs text-sultaf-muted mb-2">
                                {{ $transaksi->no_meja ? 'Table ' . $transaksi->no_meja : 'Takeaway' }}
                                {{-- DIUBAH: name menjadi username --}}
                                @if ($transaksi->user) • {{ $transaksi->user->username }} @endif
                            </p>

                            <div class="space-y-1 mb-3">
                                {{-- DIUBAH: items menjadi detail_transaksi --}}
                                @foreach ($transaksi->detail_transaksi as $item)
                                    {{-- DIUBAH: qty menjadi jumlah, nama_makanan menjadi nama_menu --}}
                                    <p class="text-sm text-sultaf-ink/80">{{ $item->jumlah }}x {{ $item->menu->nama_menu }}</p>
                                @endforeach
                            </div>

                            @if ($col['actionable'])
                                <form method="POST" action="{{ route('kasir.pesanan.update', $transaksi) }}">
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

    <p class="text-xs text-sultaf-muted mt-4">{{ $selesaiHariIni }} pesanan telah diselesaikan hari ini.</p>
</x-layouts.kasir>