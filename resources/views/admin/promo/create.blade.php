@php
    // =========================================================================
    // 1. BAGIAN LOGIKA & DATA (PHP)
    // =========================================================================
    $title       = 'Kirim Notifikasi - Admin Sultaf';
    $pageTitle   = 'Kirim Notifikasi';
    $urlStore    = route('admin.promo.store');
    $urlIndex    = route('admin.promo.index');
    $oldJudul    = old('judul');
    $oldIsiPesan = old('isi_pesan');
    $oldTarget   = old('target', 'all');
    $oldIdUser   = old('id_user');
    $usersList   = $users ?? [];
@endphp

<x-layouts.admin :title="$title" :pageTitle="$pageTitle">

    <!-- =========================================================================
        2. BAGIAN STYLING (CSS NATIVE - TANPA TAILWIND)
        ========================================================================= -->
    <style>
        :root {
            --sultaf-maroon: #7A1C1C;
            --sultaf-maroon-dark: #541010;
            --sultaf-ink: #1F2937;
            --sultaf-muted: #6B7280;
            --sultaf-border: #E5E7EB;
            --sultaf-danger: #EF4444;
            --white: #FFFFFF;
        }

        .promo-form-container {
            max-width: 42rem; /* max-w-2xl */
        }

        .promo-form {
            display: flex;
            flex-direction: column;
            gap: 1.5rem; /* space-y-6 */
        }

        .promo-form-group {
            display: flex;
            flex-direction: column;
        }

        .promo-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--sultaf-ink);
            margin-bottom: 0.375rem; /* mb-1.5 */
        }

        .promo-input,
        .promo-textarea,
        .promo-select {
            width: 100%;
            border: 1px solid var(--sultaf-border);
            border-radius: 0.75rem; /* rounded-xl */
            padding: 0.625rem 1rem; /* px-4 py-2.5 */
            font-size: 0.875rem;
            background-color: var(--white);
            color: var(--sultaf-ink);
            font-family: inherit;
            transition: border-color 0.2s ease;
        }

        .promo-input:focus,
        .promo-textarea:focus,
        .promo-select:focus {
            outline: none;
            border-color: var(--sultaf-maroon);
        }

        .promo-error {
            color: var(--sultaf-danger);
            font-size: 0.75rem;
            margin-top: 0.25rem;
        }

        .promo-hidden {
            display: none;
        }

        .promo-form-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem; /* gap-3 */
            padding-top: 1rem; /* pt-4 */
            border-top: 1px solid var(--sultaf-border);
        }

        .promo-btn-submit {
            background-color: var(--sultaf-maroon);
            color: var(--white);
            font-size: 0.875rem;
            font-weight: 600;
            border: none;
            border-radius: 0.75rem;
            padding: 0.625rem 1.5rem; /* px-6 py-2.5 */
            cursor: pointer;
            font-family: inherit;
            transition: background-color 0.2s ease;
        }

        .promo-btn-submit:hover {
            background-color: var(--sultaf-maroon-dark);
        }

        .promo-btn-cancel {
            font-size: 0.875rem;
            color: var(--sultaf-muted);
            font-weight: 500;
            padding: 0.625rem 1rem; /* px-4 py-2.5 */
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .promo-btn-cancel:hover {
            color: var(--sultaf-ink);
        }
    </style>

    <!-- =========================================================================
         3. BAGIAN STRUKTUR TAMPILAN (HTML)
         ========================================================================= -->
    <div class="promo-form-container">
        <form method="POST" action="{{ $urlStore }}" class="promo-form">
            @csrf

            <div class="promo-form-group">
                <label for="judul" class="promo-label">Judul Notifikasi</label>
                <input type="text" id="judul" name="judul" value="{{ $oldJudul }}"
                       placeholder="e.g. Promo Akhir Pekan" required
                       class="promo-input">
                @error('judul')
                    <p class="promo-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="promo-form-group">
                <label for="isi_pesan" class="promo-label">Isi Pesan</label>
                <textarea id="isi_pesan" name="isi_pesan" rows="4" placeholder="Tulis isi pesan..." required
                          class="promo-textarea">{{ $oldIsiPesan }}</textarea>
                @error('isi_pesan')
                    <p class="promo-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="promo-form-group">
                <label for="target" class="promo-label">Penerima</label>
                <select name="target" id="target" onchange="toggleUserSelect()" required class="promo-select">
                    <option value="all" {{ $oldTarget === 'all' ? 'selected' : '' }}>Semua Member &amp; Customer</option>
                    <option value="specific" {{ $oldTarget === 'specific' ? 'selected' : '' }}>User Tertentu</option>
                </select>
            </div>

            <div id="user-select-wrapper" class="promo-form-group {{ $oldTarget === 'specific' ? '' : 'promo-hidden' }}">
                <label for="id_user" class="promo-label">Pilih User</label>
                <select id="id_user" name="id_user" class="promo-select">
                    <option value="">-- Pilih User --</option>
                    @foreach ($usersList as $user)
                        <option value="{{ $user->id_user }}" {{ $oldIdUser == $user->id_user ? 'selected' : '' }}>
                            {{ $user->username }} ({{ $user->role }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="promo-form-actions">
                <button type="submit" class="promo-btn-submit">
                    Kirim Notifikasi
                </button>
                <a href="{{ $urlIndex }}" class="promo-btn-cancel">
                    Batal
                </a>
            </div>
        </form>
    </div>

    <script>
        function toggleUserSelect() {
            const target = document.getElementById('target').value;
            const wrapper = document.getElementById('user-select-wrapper');
            wrapper.classList.toggle('promo-hidden', target !== 'specific');
        }
    </script>

</x-layouts.admin>