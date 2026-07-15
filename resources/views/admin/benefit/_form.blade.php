@csrf

@if ($errors->any())
    <div class="mb-6 bg-red-50 border border-red-200 text-red-600 text-sm rounded-xl px-4 py-3 space-y-1">
        @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
    </div>
@endif

<div class="bg-white rounded-2xl border border-gray-100 p-6 max-w-xl space-y-5">

    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Promosi</label>
        <input type="text" name="nama_benefit" value="{{ old('nama_benefit', $benefit->nama_benefit ?? '') }}"
               placeholder="e.g. 5% Off Total Bill" required
               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-sultaf-maroon">
    </div>

    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Deskripsi</label>
        <textarea name="deskripsi" rows="2" placeholder="Deskripsi singkat promosi..."
                  class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-sultaf-maroon">{{ old('deskripsi', $benefit->deskripsi ?? '') }}</textarea>
    </div>

    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Minimal Poin Dibutuhkan</label>
        <input type="number" name="poin_dibutuhkan" value="{{ old('poin_dibutuhkan', $benefit->poin_dibutuhkan ?? '') }}"
               min="1" placeholder="500" required
               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-sultaf-maroon">
        <p class="text-xs text-gray-400 mt-1">Member yang mengumpulkan poin sejumlah ini atau lebih bisa klaim promosi.</p>
    </div>

    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tipe Reward</label>
        <div class="grid grid-cols-2 gap-3">
            <label class="cursor-pointer">
                <input type="radio" name="tipe" value="diskon" class="peer hidden"
                       {{ old('tipe', $benefit->tipe ?? 'diskon') === 'diskon' ? 'checked' : '' }}
                       onchange="document.getElementById('diskonField').classList.remove('hidden'); document.getElementById('menuField').classList.add('hidden')">
                <div class="text-center py-2.5 rounded-xl border border-gray-200 text-sm font-medium text-gray-600
                            peer-checked:bg-sultaf-maroon peer-checked:text-white peer-checked:border-sultaf-maroon">
                    Potongan Harga
                </div>
            </label>
            <label class="cursor-pointer">
                <input type="radio" name="tipe" value="gratis_menu" class="peer hidden"
                       {{ old('tipe', $benefit->tipe ?? '') === 'gratis_menu' ? 'checked' : '' }}
                       onchange="document.getElementById('menuField').classList.remove('hidden'); document.getElementById('diskonField').classList.add('hidden')">
                <div class="text-center py-2.5 rounded-xl border border-gray-200 text-sm font-medium text-gray-600
                            peer-checked:bg-sultaf-maroon peer-checked:text-white peer-checked:border-sultaf-maroon">
                    Menu Gratis
                </div>
            </label>
        </div>
    </div>

    <div id="diskonField" class="{{ old('tipe', $benefit->tipe ?? 'diskon') === 'gratis_menu' ? 'hidden' : '' }}">
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nilai Diskon (%)</label>
        <input type="number" name="nilai_diskon" value="{{ old('nilai_diskon', $benefit->nilai_diskon ?? '') }}"
               min="0" max="100" step="0.5" placeholder="5"
               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-sultaf-maroon">
    </div>

    <div id="menuField" class="{{ old('tipe', $benefit->tipe ?? 'diskon') === 'gratis_menu' ? '' : 'hidden' }}">
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Pilih Menu Gratis</label>
        <select name="menu_id"
                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-sultaf-maroon">
            <option value="">-- Pilih menu --</option>
            @foreach ($menus as $m)
                <option value="{{ $m->id }}" {{ old('menu_id', $benefit->menu_id ?? '') == $m->id ? 'selected' : '' }}>
                    {{ $m->nama_makanan }}
                </option>
            @endforeach
        </select>
    </div>

    <label class="flex items-center gap-2 cursor-pointer pt-2">
        <input type="checkbox" name="aktif" value="1"
               {{ old('aktif', $benefit->aktif ?? true) ? 'checked' : '' }}
               class="w-4 h-4 rounded border-gray-300 text-sultaf-maroon focus:ring-sultaf-maroon">
        <span class="text-sm text-gray-700">Aktifkan promosi ini</span>
    </label>
</div>

<div class="flex items-center gap-3 mt-6">
    <button type="submit" class="bg-sultaf-maroon hover:bg-sultaf-maroon-dark text-white font-semibold rounded-xl px-6 py-3 text-sm transition-colors">
        {{ isset($benefit) ? 'Simpan Perubahan' : 'Tambah Promosi' }}
    </button>
    <a href="{{ route('admin.benefit.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700 px-4">Batal</a>
</div>
