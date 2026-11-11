<x-layouts.admin title="Tambah Menu - Admin Sultaf" pageTitle="Tambah Menu Baru">

    <div class="w-full">
        <form method="POST" action="{{ route('admin.menu.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- ================= KOLOM KIRI (FORM UTAMA) ================= --}}
                <div class="lg:col-span-2 space-y-5 bg-white rounded-2xl border border-sultaf-border p-6">

                    {{-- Nama Menu --}}
                    <div>
                        <label class="block text-sm font-semibold text-sultaf-ink mb-1.5">Nama Menu</label>
                        <input type="text" name="nama_menu" value="{{ old('nama_menu') }}"
                            placeholder="e.g. Nasi Goreng Sultaf" required
                            class="w-full border border-sultaf-border rounded-xl px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-sultaf-maroon">
                        @error('nama_menu')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Kategori (Hanya untuk Tampilan, tidak disimpan ke DB) --}}
                    <div>
                        <label class="block text-sm font-semibold text-sultaf-ink mb-1.5">Kategori</label>
                        <select name="kategori" required
                            class="w-full border border-sultaf-border rounded-xl px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-sultaf-maroon">
                            <option value="makanan" {{ old('kategori') == 'makanan' ? 'selected' : '' }}>Makanan</option>
                            <option value="minuman" {{ old('kategori') == 'minuman' ? 'selected' : '' }}>Minuman</option>
                            <option value="camilan" {{ old('kategori') == 'camilan' ? 'selected' : '' }}>Camilan</option>
                        </select>
                    </div>

                    {{-- Harga Jual --}}
                    <div>
                        <label class="block text-sm font-semibold text-sultaf-ink mb-1.5">Harga Jual (Rp)</label>
                        <input type="number" name="harga" value="{{ old('harga') }}" placeholder="25000" required
                            min="0"
                            class="w-full border border-sultaf-border rounded-xl px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-sultaf-maroon">
                        @error('harga')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Status Ketersediaan --}}
                    <div>
                        <label class="block text-sm font-semibold text-sultaf-ink mb-1.5">Status Ketersediaan</label>
                        <select name="status_ketersediaan" required
                            class="w-full border border-sultaf-border rounded-xl px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-sultaf-maroon">
                            <option value="tersedia" {{ old('status_ketersediaan') === 'tersedia' ? 'selected' : '' }}>
                                Tersedia</option>
                            <option value="habis" {{ old('status_ketersediaan') === 'habis' ? 'selected' : '' }}>Habis
                            </option>
                        </select>
                        @error('status_ketersediaan')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                </div>

                {{-- ================= KOLOM KANAN (FOTO) ================= --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl border border-sultaf-border p-6">
                        <h3 class="font-semibold text-sultaf-ink mb-4">Foto Menu</h3>

                        <label
                            class="border-2 border-dashed border-sultaf-border rounded-xl p-6 flex flex-col items-center justify-center text-center cursor-pointer hover:bg-sultaf-cream/50 transition-colors">
                            <svg class="w-8 h-8 text-sultaf-muted mb-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                            <span class="text-sm font-medium text-sultaf-ink">Klik untuk pilih foto</span>
                            <span class="text-xs text-sultaf-muted mt-1">JPG/PNG, maksimal 2MB.</span>
                            <input type="file" name="foto_menu" accept="image/*" class="hidden">
                        </label>
                        @error('foto_menu')
                        <p class="text-red-500 text-xs mt-2 text-center">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex items-center gap-3 mt-6">
                <button type="submit"
                    class="bg-sultaf-maroon hover:bg-sultaf-maroon-dark text-white text-sm font-semibold rounded-xl px-6 py-2.5 transition-colors">
                    Simpan Menu
                </button>
                <a href="{{ route('admin.menu.index') }}"
                    class="text-sm text-sultaf-muted hover:text-sultaf-ink font-medium px-4 py-2.5">
                    Batal
                </a>
            </div>
        </form>
    </div>

</x-layouts.admin>