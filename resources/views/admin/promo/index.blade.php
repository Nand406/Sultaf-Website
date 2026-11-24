@php
    // =========================================================================
    // 1. BAGIAN LOGIKA & DATA (PHP)
    // =========================================================================
    $pageTitle = 'Kirim Notifikasi';
    $title = 'Kirim Notifikasi - Admin Sultaf';
    $successMessage = session('success');
    $errorMessage = session('error');
    $urlCreate = route('admin.promo.create');
@endphp

<x-layouts.admin :title="$title" :pageTitle="$pageTitle">

    <!-- =========================================================================
         2. BAGIAN STYLING (CSS NATIVE - TANPA TAILWIND)
         ========================================================================= -->
    <style>
        :root {
            --sultaf-maroon: #7A1C1C;
            --sultaf-maroon-dark: #541010;
            --sultaf-cream: #FDFBF7;
            --sultaf-ink: #1F2937;
            --sultaf-muted: #6B7280;
            --sultaf-border: #E5E7EB;
            --sultaf-success: #15803D;
            --sultaf-success-soft: #DCFCE7;
            --sultaf-danger: #DC2626;
            --sultaf-danger-soft: #FEE2E2;
            --white: #FFFFFF;
        }

        /* Alert Sukses & Error */
        .promo-alert-success {
            margin-bottom: 1.5rem;
            background-color: var(--sultaf-success-soft);
            border: 1px solid rgba(21, 128, 61, 0.2);
            color: var(--sultaf-success);
            font-size: 0.875rem;
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
        }

        .promo-alert-error {
            margin-bottom: 1.5rem;
            background-color: var(--sultaf-danger-soft);
            border: 1px solid rgba(220, 38, 38, 0.2);
            color: var(--sultaf-danger);
            font-size: 0.875rem;
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
        }

        /* Header Aksi */
        .promo-action-header {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        @media (min-width: 640px) {
            .promo-action-header {
                flex-direction: row;
                align-items: center;
            }
        }

        .promo-description {
            font-size: 0.875rem;
            color: var(--sultaf-muted);
            max-width: 36rem;
            margin: 0;
        }

        .promo-btn-primary {
            background-color: var(--sultaf-maroon);
            color: var(--white);
            font-size: 0.875rem;
            font-weight: 600;
            border-radius: 0.75rem;
            padding: 0.625rem 1.25rem;
            text-decoration: none;
            transition: background-color 0.2s ease;
            flex-shrink: 0;
            display: inline-block;
        }

        .promo-btn-primary:hover {
            background-color: var(--sultaf-maroon-dark);
        }

        /* Card & Tabel */
        .promo-card {
            background-color: var(--white);
            border: 1px solid var(--sultaf-border);
            border-radius: 0.75rem;
            overflow: hidden;
        }

        .promo-table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        .promo-table {
            width: 100%;
            font-size: 0.875rem;
            border-collapse: collapse;
        }

        .promo-table thead tr {
            text-align: left;
            color: var(--sultaf-muted);
            font-size: 0.75rem;
            text-transform: uppercase;
            background-color: rgba(253, 251, 247, 0.85);
            border-bottom: 1px solid var(--sultaf-border);
        }

        .promo-table th {
            padding: 0.75rem 1.25rem;
            font-weight: 500;
        }

        .promo-table tbody tr {
            border-bottom: 1px solid var(--sultaf-border);
        }

        .promo-table tbody tr:last-child {
            border-bottom: none;
        }

        .promo-table td {
            padding: 0.75rem 1.25rem;
        }

        .promo-text-right {
            text-align: right;
        }

        .promo-cell-title {
            font-weight: 600;
            color: var(--sultaf-ink);
        }

        .promo-cell-muted {
            color: var(--sultaf-muted);
        }

        .promo-cell-ink {
            color: var(--sultaf-ink);
        }

        .promo-btn-delete {
            background: none;
            border: none;
            color: var(--sultaf-danger);
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            font-family: inherit;
            padding: 0;
        }

        .promo-btn-delete:hover {
            text-decoration: underline;
        }

        .promo-empty-state {
            padding: 3rem 1.25rem;
            text-align: center;
            color: var(--sultaf-muted);
        }

        .promo-pagination {
            margin-top: 1.25rem;
        }
    </style>

    <!-- =========================================================================
         3. BAGIAN STRUKTUR TAMPILAN (HTML)
         ========================================================================= -->
    @if ($successMessage)
        <div class="promo-alert-success">
            ✓ {{ $successMessage }}
        </div>
    @endif

    @if ($errorMessage)
        <div class="promo-alert-error">
            ✕ {{ $errorMessage }}
        </div>
    @endif

    <div class="promo-action-header">
        <p class="promo-description">
            Kirim notifikasi ke member atau customer. Notifikasi akan muncul di halaman mereka.
        </p>
        <a href="{{ $urlCreate }}" class="promo-btn-primary">
            + Kirim Notifikasi
        </a>
    </div>

    <div class="promo-card">
        <div class="promo-table-wrapper">
            <table class="promo-table">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Pesan</th>
                        <th>Penerima</th>
                        <th>Tanggal</th>
                        <th class="promo-text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($notifikasis as $notif)
                        <tr>
                            <td class="promo-cell-title">{{ $notif->judul }}</td>
                            <td class="promo-cell-muted">{{ \Str::limit($notif->isi_pesan, 50) }}</td>
                            <td class="promo-cell-ink">{{ $notif->user->username ?? 'N/A' }}</td>
                            <td class="promo-cell-muted">
                                {{ $notif->created_at ? $notif->created_at->format('d M Y H:i') : '-' }}
                            </td>
                            <td class="promo-text-right">
                                <form method="POST" action="{{ route('admin.promo.destroy', $notif->id_notifikasi) }}"
                                    onsubmit="return confirm('Hapus notifikasi ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="promo-btn-delete">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="promo-empty-state">
                                Belum ada notifikasi yang dikirim.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($notifikasis->hasPages())
        <div class="promo-pagination">
            {{ $notifikasis->links() }}
        </div>
    @endif

    {{-- ===================== CUSTOM DELETE MODAL ===================== --}}
    <div id="deleteModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-2xl p-6 w-full max-w-sm mx-4 shadow-2xl">
            
            <div class="text-center">
                <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-sultaf-ink mb-2">Hapus Notifikasi?</h3>
                <p class="text-sm text-sultaf-muted mb-6">
                    Apakah Anda yakin ingin menghapus notifikasi <strong id="deleteItemName" class="text-sultaf-ink"></strong>?<br>Tindakan ini tidak dapat dibatalkan.
                </p>
            </div>

            <div class="flex gap-3">
                <button onclick="closeDeleteModal()" class="flex-1 py-2.5 rounded-xl border border-sultaf-border text-sultaf-ink font-semibold hover:bg-sultaf-cream transition-colors">
                    Batal
                </button>
                <button onclick="submitDelete()" 
                        style="background-color: #dc2626; color: #ffffff;" 
                        class="flex-1 py-2.5 rounded-xl font-semibold hover:bg-red-700 shadow-sm transition-colors">
                    Ya
                </button>
            </div>
        </div>
    </div>

    {{-- SCRIPT UNTUK MODAL --}}
    <script>
        let formToSubmit = null;

        function openDeleteModal(formId, itemName) {
            formToSubmit = document.getElementById(formId);
            document.getElementById('deleteItemName').textContent = itemName;
            const modal = document.getElementById('deleteModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeDeleteModal() {
            formToSubmit = null;
            const modal = document.getElementById('deleteModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function submitDelete() {
            if (formToSubmit) {
                formToSubmit.submit();
            }
        }
    </script>

</x-layouts.admin>