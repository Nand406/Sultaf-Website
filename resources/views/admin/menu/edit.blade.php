<x-layouts.admin title="Edit Menu - Admin Sultaf" pageTitle="Edit Menu">

    <form method="POST" action="{{ route('admin.menu.update', $menu) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 w-full">
            
            {{-- ================= KOLOM KIRI (FORM UTAMA) ================= --}}
            <div class="lg:col-span-2 space-y-5 bg-white rounded-2xl border border-sultaf-border p-6">
                
                {{-- Nama Menu --}}
                <div>
                    <label class="block text-sm font-semibold text-sultaf-ink mb-1.5">Nama Menu</label>
                    <input type="text" name="nama_menu" 
                           value="{{ old('nama_menu', $menu->nama_menu) }}" 
                           placeholder="e.g. Mandi Chicken" required
                           class="w-full border border-sultaf-border rounded-xl px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-sultaf-maroon">
                </div>

                {{-- Deskripsi (Tidak ada di DB baru) --}}
                <div>
                    <label class="block text-sm font-semibold text-sultaf-ink mb-1.5">Deskripsi</label>
                    <textarea rows="3" placeholder="Deskripsi singkat menu..." disabled
                              class="w-full border border-sultaf-border rounded-xl px-4 py-2.5 text-sm bg-gray-50 text-gray-400 focus:outline-none cursor-not-allowed"></textarea>
                    <p class="text-xs text-sultaf-muted mt-1">*Kolom ini tidak tersedia di database baru.</p>
                </div>

                {{-- Kategori (Tidak ada di DB baru) --}}
                <div>
                    <label class="block text-sm font-semibold text-sultaf-ink mb-1.5">Kategori</label>
                    <select disabled class="w-full border border-sultaf-border rounded-xl px-4 py-2.5 text-sm bg-gray-50 text-gray-400 focus:outline-none cursor-not-allowed">
                        <option>Pilih kategori</option>
                    </select>
                    <p class="text-xs text-sultaf-muted mt-1">*Kolom ini tidak tersedia di database baru.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    {{-- Harga Jual --}}
                    <div>
                        <label class="block text-sm font-semibold text-sultaf-ink mb-1.5">Harga Jual (Rp)</label>
                        <input type="number" name="harga" 
                               value="{{ old('harga', $menu->harga) }}" 
                               placeholder="75000" required min="0"
                               class="w-full border border-sultaf-border rounded-xl px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-sultaf-maroon">
                    </div>

                    {{-- Harga Modal (Tidak ada di DB baru) --}}
                    <div>
                        <label class="block text-sm font-semibold text-sultaf-ink mb-1.5">Harga Modal / HPP (Rp)</label>
                        <input type="number" placeholder="0" disabled
                               class="w-full border border-sultaf-border rounded-xl px-4 py-2.5 text-sm bg-gray-50 text-gray-400 focus:outline-none cursor-not-allowed">
                        <p class="text-xs text-sultaf-muted mt-1">*Tidak tersedia di database baru.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    {{-- Rating (Tidak ada di DB baru) --}}
                    <div>
                        <label class="block text-sm font-semibold text-sultaf-ink mb-1.5">Rating (0–5)</label>
                        <input type="number" placeholder="0" disabled
                               class="w-full border border-sultaf-border rounded-xl px-4 py-2.5 text-sm bg-gray-50 text-gray-400 focus:outline-none cursor-not-allowed">
                    </div>

                    {{-- Level Pedas (Tidak ada di DB baru) --}}
                    <div>
                        <label class="block text-sm font-semibold text-sultaf-ink mb-1.5">Level Pedas (0–3)</label>
                        <select disabled class="w-full border border-sultaf-border rounded-xl px-4 py-2.5 text-sm bg-gray-50 text-gray-400 focus:outline-none cursor-not-allowed">
                            <option>Tidak pedas</option>
                        </select>
                    </div>
                </div>

                {{-- Status Ketersediaan --}}
                <div class="pt-2">
                    <label class="block text-sm font-semibold text-sultaf-ink mb-1.5">Status Ketersediaan</label>
                    <select name="status_ketersediaan" required
                            class="w-full border border-sultaf-border rounded-xl px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-sultaf-maroon">
                        <option value="tersedia" {{ old('status_ketersediaan', $menu->status_ketersediaan) === 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                        <option value="habis" {{ old('status_ketersediaan', $menu->status_ketersediaan) === 'habis' ? 'selected' : '' }}>Habis</option>
                    </select>
                </div>

            </div>

            {{-- ================= KOLOM KANAN (FOTO) ================= --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl border border-sultaf-border p-6">
                    <h3 class="font-semibold text-sultaf-ink mb-4">Foto Menu</h3>
                    
                    @if ($menu->foto_menu)
                        <div class="mb-4">
                            <img src="{{ asset('storage/' . $menu->foto_menu) }}" alt="Foto Menu" class="w-full h-40 object-cover rounded-xl border border-sultaf-border">
                            <p class="text-xs text-sultaf-muted mt-2 text-center">Foto saat ini. Biarkan kosong jika tidak ingin mengubah.</p>
                        </div>
                    @endif

                    <label class="border-2 border-dashed border-sultaf-border rounded-xl p-6 flex flex-col items-center justify-center text-center cursor-pointer hover:bg-sultaf-cream/50 transition-colors">
                        <svg class="w-8 h-8 text-sultaf-muted mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        <span class="text-sm font-medium text-sultaf-ink">Klik untuk pilih foto</span>
                        <span class="text-xs text-sultaf-muted mt-1">JPG/PNG, maksimal 2MB.</span>
                        <input type="file" name="foto_menu" accept="image/*" class="hidden">
                    </label>
                </div>
            </div>
        </div>

        {{-- Tombol Aksi --}}
        <div class="flex items-center gap-3 mt-6">
            <button type="submit" class="bg-sultaf-maroon hover:bg-sultaf-maroon-dark text-white text-sm font-semibold rounded-xl px-6 py-2.5 transition-colors">
                Simpan Perubahan
            </button>
            <a href="{{ route('admin.menu.index') }}" class="text-sm text-sultaf-muted hover:text-sultaf-ink font-medium px-4 py-2.5">
                Batal
            </a>
        </div>
    </form>

</x-layouts.admin>