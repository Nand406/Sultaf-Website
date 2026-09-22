@csrf

@if ($errors->any())
    <div class="mb-6 bg-sultaf-danger-soft border border-sultaf-danger/20 text-sultaf-danger text-sm rounded-xl px-4 py-3 space-y-1">
        @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-[1fr_320px] gap-6">

    <div class="staff-card p-6 space-y-5">
        <div>
            <label class="block text-sm font-semibold text-sultaf-ink mb-1.5">Nama Menu</label>
            <input type="text" name="nama_makanan" value="{{ old('nama_makanan', $menu->nama_makanan ?? '') }}"
                   placeholder="e.g. Mandi Chicken" required
                   class="w-full border border-sultaf-border rounded-xl px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-sultaf-maroon">
        </div>

        <div>
            <label class="block text-sm font-semibold text-sultaf-ink mb-1.5">Deskripsi</label>
            <textarea name="deskripsi" rows="3" placeholder="Deskripsi singkat menu..."
                      class="w-full border border-sultaf-border rounded-xl px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-sultaf-maroon">{{ old('deskripsi', $menu->deskripsi ?? '') }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-semibold text-sultaf-ink mb-1.5">Kategori</label>
            <select name="category_id" required
                    class="w-full border border-sultaf-border rounded-xl px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-sultaf-maroon">
                <option value="" disabled {{ old('category_id', $menu->category_id ?? '') ? '' : 'selected' }}>Pilih kategori</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id', $menu->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-sultaf-ink mb-1.5">Harga Jual (Rp)</label>
                <input type="number" name="harga_makanan" value="{{ old('harga_makanan', $menu->harga_makanan ?? '') }}"
                       min="0" step="500" placeholder="75000" required
                       class="w-full border border-sultaf-border rounded-xl px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-sultaf-maroon">
            </div>
            <div>
                <label class="block text-sm font-semibold text-sultaf-ink mb-1.5">Harga Modal / HPP (Rp)</label>
                <input type="number" name="harga_modal" value="{{ old('harga_modal', $menu->harga_modal ?? 0) }}"
                       min="0" step="500" placeholder="35000"
                       class="w-full border border-sultaf-border rounded-xl px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-sultaf-maroon">
                <p class="text-xs text-sultaf-muted mt-1">Dipakai untuk hitung keuntungan di dashboard Owner.</p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-sultaf-ink mb-1.5">Rating (0–5)</label>
                <input type="number" name="rating" value="{{ old('rating', $menu->rating ?? 0) }}"
                       min="0" max="5" step="0.1"
                       class="w-full border border-sultaf-border rounded-xl px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-sultaf-maroon">
            </div>
            <div>
                <label class="block text-sm font-semibold text-sultaf-ink mb-1.5">Level Pedas (0–3)</label>
                <select name="spice_level"
                        class="w-full border border-sultaf-border rounded-xl px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-sultaf-maroon">
                    @for ($i = 0; $i <= 3; $i++)
                        <option value="{{ $i }}" {{ old('spice_level', $menu->spice_level ?? 0) == $i ? 'selected' : '' }}>
                            {{ $i === 0 ? 'Tidak pedas' : str_repeat('🌶️', $i) }}
                        </option>
                    @endfor
                </select>
            </div>
        </div>

        <div class="flex items-center gap-6 pt-2">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_halal" value="1"
                       {{ old('is_halal', $menu->is_halal ?? true) ? 'checked' : '' }}
                       class="w-4 h-4 rounded border-sultaf-border text-sultaf-maroon focus:ring-sultaf-maroon">
                <span class="text-sm text-sultaf-ink">Halal</span>
            </label>
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="habis" value="1"
                       {{ old('habis', $menu->habis ?? false) ? 'checked' : '' }}
                       class="w-4 h-4 rounded border-sultaf-border text-sultaf-danger focus:ring-sultaf-danger">
                <span class="text-sm text-sultaf-ink">Tandai sebagai habis</span>
            </label>
        </div>
    </div>

    <div class="staff-card p-6 h-fit">
        <label class="block text-sm font-semibold text-sultaf-ink mb-3">Foto Menu</label>

        @if (!empty($menu) && $menu->foto_makanan)
            <div class="w-full h-40 rounded-xl overflow-hidden mb-3 bg-sultaf-cream-dark">
                <img src="{{ asset('storage/'.$menu->foto_makanan) }}" class="w-full h-full object-cover">
            </div>
            <p class="text-xs text-sultaf-muted mb-3">Upload foto baru untuk mengganti.</p>
        @endif

        <label class="flex flex-col items-center justify-center gap-2 border-2 border-dashed border-sultaf-border rounded-xl py-8 cursor-pointer hover:border-sultaf-maroon transition-colors">
            <svg class="w-6 h-6 text-sultaf-muted" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 16V4M7 9l5-5 5 5M5 20h14"/></svg>
            <span id="fileLabel" class="text-sm text-sultaf-muted font-medium text-center px-2">Klik untuk pilih foto</span>
            <input type="file" name="foto" accept="image/*" class="hidden"
                   onchange="document.getElementById('fileLabel').textContent = this.files[0]?.name ?? 'Klik untuk pilih foto'">
        </label>
        <p class="text-xs text-sultaf-muted mt-2">JPG/PNG, maksimal 2MB.</p>
    </div>
</div>

<div class="flex items-center gap-3 mt-6">
    <button type="submit" class="bg-sultaf-maroon hover:bg-sultaf-maroon-dark text-white font-semibold rounded-xl px-6 py-3 text-sm transition-colors">
        {{ isset($menu) ? 'Simpan Perubahan' : 'Tambah Menu' }}
    </button>
    <a href="{{ route('admin.menu.index') }}" class="text-sm font-medium text-sultaf-muted hover:text-sultaf-ink px-4">Batal</a>
</div>
