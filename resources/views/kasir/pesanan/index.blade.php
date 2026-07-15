<x-layouts.kasir title="Status Pesanan - Kasir" pageTitle="Order Status Board">

    @if (session('success'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-xl px-4 py-3">
            ✓ {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-600 text-sm rounded-xl px-4 py-3">
            {{ session('error') }}
        </div>
    @endif

    @php
        // Class ditulis literal (bukan digabung dinamis) supaya terbaca Tailwind JIT compiler.
        // 'actionable' => false berarti kasir hanya bisa MELIHAT kolom ini, tidak bisa mengubahnya
        // (statusnya dikendalikan oleh Dapur).
        $columns = [
            'pending' => [
                'label' => 'Pending', 'actionable' => false, 'note' => 'Menunggu dapur mulai memasak',
                'bgSoft' => 'bg-amber-50/50', 'border' => 'border-amber-100', 'dot' => 'bg-amber-500',
            ],
            'cooking' => [
                'label' => 'Cooking', 'actionable' => false, 'note' => 'Sedang dimasak dapur',
                'bgSoft' => 'bg-orange-50/50', 'border' => 'border-orange-100', 'dot' => 'bg-orange-500',
            ],
            'ready' => [
                'label' => 'Ready', 'actionable' => true, 'next' => 'diantar', 'nextLabel' => 'Antar Pesanan',
                'bgSoft' => 'bg-emerald-50/50', 'border' => 'border-emerald-100', 'dot' => 'bg-emerald-500',
                'btn' => 'bg-emerald-600 hover:bg-emerald-700',
            ],
            'diantar' => [
                'label' => 'Diantar', 'actionable' => true, 'next' => 'selesai', 'nextLabel' => 'Selesaikan',
                'bgSoft' => 'bg-blue-50/50', 'border' => 'border-blue-100', 'dot' => 'bg-blue-500',
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
                        <span class="font-semibold text-gray-800">{{ $col['label'] }}</span>
                        @if (! $col['actionable'])
                            <span class="text-[10px] font-semibold uppercase text-gray-400 bg-white rounded-full px-2 py-0.5">Lihat saja</span>
                        @endif
                    </div>
                    <span class="text-xs font-semibold bg-white rounded-full px-2.5 py-1 text-gray-500">
                        {{ isset($pesanan[$key]) ? $pesanan[$key]->count() : 0 }} Orders
                    </span>
                </div>

                <div class="p-3 space-y-3">
                    @forelse ($pesanan[$key] ?? [] as $transaksi)
                        <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-serif text-lg font-bold text-gray-800">#{{ substr($transaksi->kode_transaksi, -4) }}</span>
                                <span class="text-xs font-semibold px-2 py-1 rounded-full bg-gray-100 text-gray-500">
                                    {{ $transaksi->tgl_transaksi->diffForHumans(null, true) }}
                                </span>
                            </div>

                            <p class="text-xs text-gray-400 mb-2">
                                {{ $transaksi->nomor_meja ? 'Table ' . $transaksi->nomor_meja : 'Takeaway' }}
                                @if ($transaksi->user) • {{ $transaksi->user->name }} @endif
                            </p>

                            <div class="space-y-1 mb-3">
                                @foreach ($transaksi->items as $item)
                                    <p class="text-sm text-gray-700">{{ $item->qty }}x {{ $item->menu->nama_makanan }}</p>
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
                                {{-- Read-only: kasir cuma bisa lihat, tombolnya nonaktif --}}
                                <div class="w-full bg-gray-100 text-gray-400 text-xs font-semibold rounded-lg py-2.5 text-center cursor-not-allowed">
                                    {{ $col['note'] }}
                                </div>
                            @endif
                        </div>
                    @empty
                        <p class="text-center text-gray-300 text-sm py-10">Tidak ada pesanan</p>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>

    <p class="text-xs text-gray-400 mt-4">{{ $selesaiHariIni }} pesanan telah diselesaikan hari ini.</p>
</x-layouts.kasir>
