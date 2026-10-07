<x-layouts.admin title="Edit Promosi - Admin Sultaf" pageTitle="Edit Promosi">

    <div class="max-w-2xl">
        <form method="POST" action="{{ route('admin.benefit.update', $benefit) }}" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Nama Promo --}}
            <div>
                <label class="block text-sm font-semibold text-sultaf-ink mb-1.5">Nama Promo</label>
                <input type="text" name="nama_promo"
                       value="{{ old('nama_promo', $benefit->nama_promo) }}"
                       placeholder="e.g. Diskon Member Setia" required
                       class="w-full border border-sultaf-border rounded-xl px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-sultaf-maroon">
                @error('nama_promo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                {{-- Minimal Poin --}}
                <div>
                    <label class="block text-sm font-semibold text-sultaf-ink mb-1.5">Minimal Poin</label>
                    <input type="number" name="minimal_poin"
                           value="{{ old('minimal_poin', $benefit->minimal_poin) }}"
                           placeholder="100" required min="0"
                           class="w-full border border-sultaf-border rounded-xl px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-sultaf-maroon">
                    <p class="text-xs text-sultaf-muted mt-1">Poin minimum yang dibutuhkan member.</p>
                    @error('minimal_poin') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Potongan Harga --}}
                <div>
                    <label class="block text-sm font-semibold text-sultaf-ink mb-1.5">Potongan Harga (Rp)</label>
                    <input type="number" name="potongan_harga"
                           value="{{ old('potongan_harga', $benefit->potongan_harga) }}"
                           placeholder="15000" required min="0"
                           class="w-full border border-sultaf-border rounded-xl px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-sultaf-maroon">
                    <p class="text-xs text-sultaf-muted mt-1">Nominal potongan dalam Rupiah.</p>
                    @error('potongan_harga') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex items-center gap-3 pt-4 border-t border-sultaf-border">
                <button type="submit" class="bg-sultaf-maroon hover:bg-sultaf-maroon-dark text-white text-sm font-semibold rounded-xl px-6 py-2.5 transition-colors">
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.benefit.index') }}" class="text-sm text-sultaf-muted hover:text-sultaf-ink font-medium px-4 py-2.5">
                    Batal
                </a>
            </div>
        </form>
    </div>

</x-layouts.admin>