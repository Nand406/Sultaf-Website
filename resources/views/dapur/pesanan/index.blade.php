<x-layouts.dapur title="Kitchen Display - Dapur Sultaf" pageTitle="Kitchen Display">

    @if (session('success'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-xl px-4 py-3">
            ✓ {{ session('success') }}
        </div>
    @endif

    @php
        $columns = [
            'pending' => [
                'label' => 'Pending', 'next' => 'cooking', 'nextLabel' => 'Mulai Masak',
                'bgSoft' => 'bg-amber-50/50', 'border' => 'border-amber-100', 'dot' => 'bg-amber-500',
                'btn' => 'bg-amber-600 hover:bg-amber-700',
            ],
            'cooking' => [
                'label' => 'Cooking', 'next' => 'ready', 'nextLabel' => 'Selesai Masak',
                'bgSoft' => 'bg-orange-50/50', 'border' => 'border-orange-100', 'dot' => 'bg-orange-500',
                'btn' => 'bg-orange-600 hover:bg-orange-700',
            ],
        ];
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        @foreach ($columns as $key => $col)
            <div class="{{ $col['bgSoft'] }} rounded-2xl border {{ $col['border'] }} min-h-[320px]">
                <div class="flex items-center justify-between px-4 py-3 border-b {{ $col['border'] }}">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full {{ $col['dot'] }}"></span>
                        <span class="font-semibold text-gray-800">{{ $col['label'] }}</span>
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
                                <span class="text-xs font-semibold px-2 py-1 rounded-full
                                    {{ $transaksi->tgl_transaksi->diffInMinutes(now()) > 20 ? 'bg-red-50 text-red-600' : 'bg-gray-100 text-gray-500' }}">
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

                            @if ($transaksi->catatan)
                                <p class="text-xs bg-amber-50 text-amber-700 rounded-lg px-2.5 py-1.5 mb-3">
                                    📝 {{ $transaksi->catatan }}
                                </p>
                            @endif

                            <form method="POST" action="{{ route('dapur.pesanan.update', $transaksi) }}">
                                @csrf
                                <input type="hidden" name="status" value="{{ $col['next'] }}">
                                <button type="submit"
                                        class="w-full {{ $col['btn'] }} text-white text-xs font-semibold rounded-lg py-2.5 transition-colors">
                                    {{ $col['nextLabel'] }}
                                </button>
                            </form>
                        </div>
                    @empty
                        <p class="text-center text-gray-300 text-sm py-12">Tidak ada pesanan</p>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>

    <p class="text-xs text-gray-400 mt-4">{{ $selesaiMasakHariIni }} pesanan selesai dimasak hari ini.</p>
</x-layouts.dapur>
