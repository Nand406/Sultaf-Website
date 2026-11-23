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

</x-layouts.admin>