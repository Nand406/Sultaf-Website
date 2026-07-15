<x-layouts.admin title="Promosi & Benefit - Admin Sultaf" pageTitle="Promosi & Benefit Member">

    @if (session('success'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-xl px-4 py-3">
            ✓ {{ session('success') }}
        </div>
    @endif

    <div class="flex items-center justify-between mb-6">
        <p class="text-sm text-gray-500 max-w-lg">
            Tentukan minimal poin yang harus dikumpulkan member untuk mendapatkan potongan harga
            atau menu gratis. Poin didapat otomatis dari setiap transaksi member yang terverifikasi.
        </p>
        <a href="{{ route('admin.benefit.create') }}"
           class="bg-sultaf-maroon hover:bg-sultaf-maroon-dark text-white text-sm font-semibold rounded-xl px-5 py-2.5 flex items-center gap-2 shrink-0 transition-colors">
            <span class="text-lg leading-none">+</span> Tambah Promosi
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        @forelse ($benefits as $benefit)
            <div class="bg-white rounded-2xl border border-gray-100 p-5 flex flex-col">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl bg-sultaf-maroon/10 flex items-center justify-center">
                        <svg class="w-5 h-5 text-sultaf-maroon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="5"/><path d="M8.5 13 7 21l5-2.5L17 21l-1.5-8"/></svg>
                    </div>
                    <form method="POST" action="{{ route('admin.benefit.toggle', $benefit) }}">
                        @csrf
                        <button type="submit"
                                class="text-xs font-semibold px-2.5 py-1 rounded-full transition-colors
                                       {{ $benefit->aktif ? 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                            {{ $benefit->aktif ? 'Aktif' : 'Nonaktif' }}
                        </button>
                    </form>
                </div>

                <h3 class="font-serif text-lg font-bold text-gray-800 mb-1">{{ $benefit->nama_benefit }}</h3>
                <p class="text-sm text-gray-500 mb-4 flex-1">{{ $benefit->deskripsi ?: '-' }}</p>

                <div class="bg-sultaf-cream rounded-xl p-3 mb-4">
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-500">Minimal Poin</span>
                        <span class="font-semibold text-gray-800">{{ number_format($benefit->poin_dibutuhkan) }} pts</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Reward</span>
                        <span class="font-semibold text-sultaf-maroon">
                            @if ($benefit->tipe === 'diskon')
                                {{ $benefit->nilai_diskon }}% Off
                            @else
                                Gratis: {{ $benefit->menu->nama_makanan ?? '-' }}
                            @endif
                        </span>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.benefit.edit', $benefit) }}"
                       class="flex-1 text-center text-xs font-semibold text-blue-600 border border-blue-100 hover:bg-blue-50 rounded-lg py-2">
                        Edit
                    </a>
                    <form method="POST" action="{{ route('admin.benefit.destroy', $benefit) }}" class="flex-1"
                          onsubmit="return confirm('Yakin ingin menghapus promosi ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full text-xs font-semibold text-red-600 border border-red-100 hover:bg-red-50 rounded-lg py-2">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-2xl border border-gray-100 p-16 text-center text-gray-400">
                Belum ada promosi. Klik "Tambah Promosi" untuk membuat yang pertama.
            </div>
        @endforelse
    </div>
</x-layouts.admin>
