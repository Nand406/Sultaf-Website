<x-layouts.admin title="Promosi & Benefit - Admin Sultaf" pageTitle="Promosi & Benefit Member">

    @if (session('success'))
        <div class="mb-6 bg-sultaf-success-soft border border-sultaf-success/20 text-sultaf-success text-sm rounded-xl px-4 py-3">
            ✓ {{ session('success') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <p class="text-sm text-sultaf-muted max-w-xl">
            Tentukan minimal poin yang harus dikumpulkan member untuk mendapatkan potongan harga. 
            Poin didapat otomatis dari setiap transaksi member yang terverifikasi.
        </p>
        <a href="{{ route('admin.benefit.create') }}" 
           class="bg-sultaf-maroon hover:bg-sultaf-maroon-dark text-white text-sm font-semibold rounded-xl px-5 py-2.5 transition-colors shrink-0">
            + Tambah Promosi
        </a>
    </div>

    {{-- Grid Promo --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse ($benefits as $benefit)
            <div class="bg-white rounded-2xl border border-sultaf-border p-5 flex flex-col justify-between">
                <div>
                    {{-- Header Card: Icon + Nama Promo --}}
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-10 h-10 rounded-xl bg-sultaf-cream flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-sultaf-maroon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5a1.99 1.99 0 011.414.586l7 7a2 2 0 010 2.828l-5 5a2 2 0 01-2.828 0l-7-7A1.99 1.99 0 013 10V5a2 2 0 012-2z"/>
                            </svg>
                        </div>
                    </div>

                    {{-- Nama Promo: DIUBAH dari 'nama_benefit' menjadi 'nama_promo' --}}
                    <h3 class="font-serif text-lg font-bold text-sultaf-ink mb-3">
                        {{ $benefit->nama_promo ?? 'Promo Tanpa Nama' }}
                    </h3>

                    {{-- Kotak Info --}}
                    <div class="bg-sultaf-cream/60 rounded-xl p-3 mb-4 space-y-2 text-sm">
                        <div class="flex justify-between items-center">
                            {{-- DIUBAH: 'poin_dibutuhkan' menjadi 'minimal_poin' --}}
                            <span class="text-sultaf-muted">Minimal Poin</span>
                            <span class="font-bold text-sultaf-maroon">{{ $benefit->minimal_poin ?? 0 }} pts</span>
                        </div>
                        <div class="flex justify-between items-center border-t border-sultaf-border pt-2">
                            <span class="text-sultaf-muted">Potongan Harga</span>
                            {{-- DIUBAH: 'nilai_diskon' menjadi 'potongan_harga' --}}
                            <span class="font-bold text-sultaf-success">
                                Rp {{ number_format($benefit->potongan_harga ?? 0, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex gap-2">
                    <a href="{{ route('admin.benefit.edit', $benefit) }}" 
                       class="flex-1 text-center text-blue-600 border border-sultaf-border hover:bg-blue-50 text-sm font-semibold rounded-lg py-2 transition-colors">
                        Edit
                    </a>
                    <form method="POST" action="{{ route('admin.benefit.destroy', $benefit) }}" 
                          onsubmit="return confirm('Yakin ingin menghapus promo ini?');" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full text-sultaf-danger border border-sultaf-border hover:bg-red-50 text-sm font-semibold rounded-lg py-2 transition-colors">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-2xl border border-sultaf-border p-12 text-center">
                <svg class="w-12 h-12 text-sultaf-muted mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5a1.99 1.99 0 011.414.586l7 7a2 2 0 010 2.828l-5 5a2 2 0 01-2.828 0l-7-7A1.99 1.99 0 013 10V5a2 2 0 012-2z"/>
                </svg>
                <p class="text-sultaf-muted">Belum ada promosi. Klik "Tambah Promosi" untuk membuat yang baru.</p>
            </div>
        @endforelse
    </div>

</x-layouts.admin>