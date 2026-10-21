<x-layouts.admin title="Kirim Notifikasi - Admin Sultaf" pageTitle="Kirim Notifikasi">

    <div class="max-w-2xl">
        <form method="POST" action="{{ route('admin.promo.store') }}" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-sultaf-ink mb-1.5">Judul Notifikasi</label>
                <input type="text" name="judul" value="{{ old('judul') }}"
                       placeholder="e.g. Promo Akhir Pekan" required
                       class="w-full border border-sultaf-border rounded-xl px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-sultaf-maroon">
                @error('judul') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-sultaf-ink mb-1.5">Isi Pesan</label>
                <textarea name="id_pesan" rows="4" placeholder="Tulis isi pesan..." required
                          class="w-full border border-sultaf-border rounded-xl px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-sultaf-maroon">{{ old('id_pesan') }}</textarea>
                @error('id_pesan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-sultaf-ink mb-1.5">Penerima</label>
                <select name="target" id="target" onchange="toggleUserSelect()" required
                        class="w-full border border-sultaf-border rounded-xl px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-sultaf-maroon">
                    <option value="all">Semua Member & Customer</option>
                    <option value="specific">User Tertentu</option>
                </select>
            </div>

            <div id="user-select-wrapper" class="hidden">
                <label class="block text-sm font-semibold text-sultaf-ink mb-1.5">Pilih User</label>
                <select name="id_user"
                        class="w-full border border-sultaf-border rounded-xl px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-sultaf-maroon">
                    <option value="">-- Pilih User --</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id_user }}">{{ $user->username }} ({{ $user->role }})</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-sultaf-border">
                <button type="submit" class="bg-sultaf-maroon hover:bg-sultaf-maroon-dark text-white text-sm font-semibold rounded-xl px-6 py-2.5 transition-colors">
                    Kirim Notifikasi
                </button>
                <a href="{{ route('admin.promo.index') }}" class="text-sm text-sultaf-muted hover:text-sultaf-ink font-medium px-4 py-2.5">
                    Batal
                </a>
            </div>
        </form>
    </div>

    <script>
        function toggleUserSelect() {
            const target = document.getElementById('target').value;
            const wrapper = document.getElementById('user-select-wrapper');
            wrapper.classList.toggle('hidden', target !== 'specific');
        }
    </script>

</x-layouts.admin>