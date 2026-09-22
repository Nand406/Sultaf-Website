<x-layouts.admin title="Promosi & Benefit - Admin Sultaf" pageTitle="Promosi & Benefit Member">

    @if (session('success'))
        <div class="mb-6 bg-sultaf-success-soft border border-sultaf-success/20 text-sultaf-success text-sm rounded-xl px-4 py-3">
            ✓ {{ session('success') }}
        </div>
    @endif

    <div class="flex items-center justify-between mb-6 gap-4">
        <p class="text-sm text-sultaf-muted max-w-lg">
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
            <div class="staff-card p-5 flex flex-col">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl bg-sultaf-maroon/10 flex items-center justify-center">
                        <svg class="w-5 h-5 text-sultaf-maroon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="5"/><path d="M8.5 13 7 21l5-2.5L17 21l-1.5-8"/></svg>
                    </div>
                    <form method="POST" action="{{ route('admin.benefit.toggle', $benefit) }}">
                        @csrf
                        <button type="submit"
                                class="text-xs font-semibold px-2.5 py-1 rounded-full transition-colors
                                       {{ $benefit->aktif ? 'bg-sultaf-success-soft text-sultaf-success hover:opacity-80' : 'bg-sultaf-cream-dark text-sultaf-muted hover:opacity-80' }}">
                            {{ $benefit->aktif ? 'Aktif' : 'Nonaktif' }}
                        </button>
                    </form>
                </div>

                <h3 class="font-serif text-lg font-bold text-sultaf-ink mb-1">{{ $benefit->nama_benefit }}</h3>
                <p class="text-sm text-sultaf-muted mb-4 flex-1">{{ $benefit->deskripsi ?: '-' }}</p>

                <div class="bg-sultaf-cream rounded-xl p-3 mb-4">
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-sultaf-muted">Minimal Poin</span>
                        <span class="font-semibold text-sultaf-ink">{{ number_format($benefit->poin_dibutuhkan) }} pts</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-sultaf-muted">Reward</span>
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
                        <button type="submit" class="w-full text-xs font-semibold text-sultaf-danger border border-sultaf-danger/20 hover:bg-sultaf-danger-soft rounded-lg py-2">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full staff-card p-16 text-center text-sultaf-muted">
                Belum ada promosi. Klik "Tambah Promosi" untuk membuat yang pertama.
            </div>
        @endforelse
    </div>
</x-layouts.admin>
