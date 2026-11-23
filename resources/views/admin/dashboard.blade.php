<?php
// =========================================================================
// 1. BAGIAN LOGIKA & DATA (PHP)
// =========================================================================

$title = "Dashboard - Admin Sultaf";
$pageTitle = "Dashboard Admin";

// Inisialisasi data (Ganti dengan query database Anda jika tidak memakai Laravel)
$stats = [
    'total_menu'    => $stats['total_menu'] ?? 0,
    'menu_habis'    => $stats['menu_habis'] ?? 0,
    'total_member'  => $stats['total_member'] ?? 0,
    'promosi_aktif' => $stats['promosi_aktif'] ?? 0,
];

$menuTerlaris  = $menuTerlaris ?? [];
$pesanTerakhir = $pesanTerakhir ?? [];

// Pengaturan URL (Otomatis menyesuaikan jika di luar Laravel)
$urlMenuCreate    = function_exists('route') ? route('admin.menu.create') : 'menu_create.php';
$urlBenefitCreate = function_exists('route') ? route('admin.benefit.create') : 'benefit_create.php';
$urlPromoIndex    = function_exists('route') ? route('admin.promo.index') : 'promo_index.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>

    <!-- =========================================================================
         2. BAGIAN STYLING (CSS NATIVE)
         ========================================================================= -->
    <style>
        /* Variabel Warna Tema Sultaf */
        :root {
            --sultaf-maroon: #7A1C1C;
            --sultaf-maroon-dark: #541010;
            --sultaf-gold-light: #F3D078;
            --sultaf-ink: #1F2937;
            --sultaf-muted: #6B7280;
            --sultaf-border: #E5E7EB;
            --sultaf-warning-soft: #FEF3C7;
            --bg-page: #F9FAFB;
            --white: #FFFFFF;
            --red-soft: #FCA5A5;
            --blue-soft: #EFF6FF;
            --blue-icon: #2563EB;
        }

        /* Reset Dasar */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: var(--bg-page);
            color: var(--sultaf-ink);
            line-height: 1.5;
        }

        .admin-container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 1.5rem;
        }

        /* Banner Statistik Atas */
        .stats-banner {
            background-color: var(--sultaf-maroon-dark);
            background-image: radial-gradient(rgba(255, 255, 255, 0.06) 1px, transparent 1px);
            background-size: 16px 16px;
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            color: var(--white);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }

        .stat-divider {
            padding-left: 1.5rem;
            border-left: 1px solid rgba(255, 255, 255, 0.1);
        }

        @media (max-width: 1023px) {
            .stat-item:nth-child(3) {
                padding-left: 0;
                border-left: none;
            }
        }

        .stat-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: rgba(255, 255, 255, 0.5);
            margin-bottom: 0.375rem;
        }

        .stat-value {
            font-family: Georgia, Cambria, "Times New Roman", Times, serif;
            font-size: 1.875rem;
            font-weight: 700;
            line-height: 2.25rem;
        }

        .text-gold {
            color: var(--sultaf-gold-light);
        }

        .stat-warning {
            font-size: 0.75rem;
            color: var(--red-soft);
            margin-top: 0.25rem;
        }

        /* Kartu Aksi Cepat (Quick Actions) */
        .staff-card {
            background-color: var(--white);
            border: 1px solid var(--sultaf-border);
            border-radius: 0.75rem;
        }

        .actions-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .action-card {
            padding: 1.25rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            text-decoration: none;
            transition: background-color 0.2s ease, border-color 0.2s ease;
        }

        .card-primary {
            background-color: var(--sultaf-maroon);
            border-color: var(--sultaf-maroon);
        }

        .card-primary:hover {
            background-color: var(--sultaf-maroon-dark);
            border-color: var(--sultaf-maroon-dark);
        }

        .card-hover-maroon:hover {
            border-color: rgba(122, 28, 28, 0.4);
        }

        .icon-box {
            width: 2.75rem;
            height: 2.75rem;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .icon-box-primary {
            background-color: rgba(255, 255, 255, 0.15);
            font-size: 1.25rem;
            font-weight: 300;
            color: var(--white);
        }

        .icon-box-warning {
            background-color: var(--sultaf-warning-soft);
        }

        .icon-box-blue {
            background-color: var(--blue-soft);
        }

        .icon-svg {
            width: 1.25rem;
            height: 1.25rem;
        }

        .text-maroon-dark { color: var(--sultaf-maroon-dark); }
        .text-blue { color: var(--blue-icon); }
        .text-white { color: var(--white); }

        .card-title {
            font-weight: 600;
            color: var(--sultaf-ink);
            font-size: 1rem;
        }

        .card-subtitle {
            font-size: 0.75rem;
            color: var(--sultaf-muted);
        }

        .text-white-muted {
            color: rgba(255, 255, 255, 0.7);
        }

        /* List Data Bawah (Menu & Pesan) */
        .bottom-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        .list-card {
            overflow: hidden;
        }

        .card-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--sultaf-border);
        }

        .card-header-flex {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .header-title {
            font-weight: 600;
            font-size: 1rem;
            color: var(--sultaf-ink);
        }

        .header-link {
            font-size: 0.75rem;
            color: var(--sultaf-maroon);
            font-weight: 500;
            text-decoration: none;
        }

        .header-link:hover {
            text-decoration: underline;
        }

        .list-item {
            padding: 0.75rem 1.25rem;
            border-bottom: 1px solid var(--sultaf-border);
        }

        .list-item:last-child {
            border-bottom: none;
        }

        .list-item-flex {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .item-name, .item-title {
            font-size: 0.875rem;
            color: var(--sultaf-ink);
        }

        .item-title {
            font-weight: 500;
        }

        .item-qty {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--sultaf-maroon);
        }

        .item-time {
            font-size: 0.75rem;
            color: var(--sultaf-muted);
        }

        .empty-state {
            padding: 2rem 1.25rem;
            text-align: center;
            color: var(--sultaf-muted);
            font-size: 0.875rem;
        }

        /* Responsive Breakpoints */
        @media (min-width: 640px) {
            .actions-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (min-width: 1024px) {
            .stats-banner {
                padding: 2rem;
            }
            .stats-grid {
                grid-template-columns: repeat(4, 1fr);
            }
            .bottom-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>

<!-- =========================================================================
     3. BAGIAN STRUKTUR TAMPILAN (HTML)
     ========================================================================= -->
<main class="admin-container">

    <!-- Banner Statistik Utama -->
    <div class="stats-banner">
        <div class="stats-grid">
            <div class="stat-item">
                <p class="stat-label">Total Menu</p>
                <p class="stat-value"><?= htmlspecialchars($stats['total_menu']) ?></p>
                <?php if ($stats['menu_habis'] > 0): ?>
                    <p class="stat-warning"><?= htmlspecialchars($stats['menu_habis']) ?> berstatus habis</p>
                <?php endif; ?>
            </div>
            
            <div class="stat-item stat-divider">
                <p class="stat-label">Total Member</p>
                <p class="stat-value text-gold"><?= htmlspecialchars($stats['total_member']) ?></p>
            </div>
            
            <div class="stat-item stat-divider">
                <p class="stat-label">Promosi Aktif</p>
                <p class="stat-value"><?= htmlspecialchars($stats['promosi_aktif']) ?></p>
            </div>
            
            <div class="stat-item stat-divider">
                <!-- Bagian kosong bawaan dari file asli -->
            </div>
        </div>
    </div>

    <!-- Kartu Aksi Cepat (Quick Actions) -->
    <div class="actions-grid">
        <a href="<?= htmlspecialchars($urlMenuCreate) ?>" class="staff-card action-card card-primary">
            <div class="icon-box icon-box-primary">+</div>
            <div>
                <p class="card-title text-white">Tambah Menu</p>
                <p class="card-subtitle text-white-muted">Buat item menu baru</p>
            </div>
        </a>

        <a href="<?= htmlspecialchars($urlBenefitCreate) ?>" class="staff-card action-card card-hover-maroon">
            <div class="icon-box icon-box-warning">
                <svg class="icon-svg text-maroon-dark" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="8" r="5"/>
                    <path d="M8.5 13 7 21l5-2.5L17 21l-1.5-8"/>
                </svg>
            </div>
            <div>
                <p class="card-title">Buat Promosi</p>
                <p class="card-subtitle">Atur benefit member baru</p>
            </div>
        </a>

        <a href="<?= htmlspecialchars($urlPromoIndex) ?>" class="staff-card action-card card-hover-maroon">
            <div class="icon-box icon-box-blue">
                <svg class="icon-svg text-blue" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 11.5a8.4 8.4 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.4 8.4 0 0 1-3.8-.9L3 21l1.9-5.7a8.4 8.4 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.4 8.4 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
                </svg>
            </div>
            <div>
                <p class="card-title">Kirim Pesan Promo</p>
                <p class="card-subtitle">Broadcast ke member</p>
            </div>
        </a>
    </div>

    <!-- Tabel / Daftar Data Bawah -->
    <div class="bottom-grid">
        <!-- Kolom Menu Terlaris -->
        <div class="staff-card list-card">
            <div class="card-header">
                <h3 class="header-title">Menu Terlaris</h3>
            </div>
            <div class="list-body">
                <?php if (!empty($menuTerlaris)): ?>
                    <?php foreach ($menuTerlaris as $item): ?>
                        <?php 
                            $namaMenu = is_object($item['menu']) 
                                ? ($item['menu']->nama_menu ?? 'Menu Dihapus') 
                                : ($item['menu']['nama_menu'] ?? 'Menu Dihapus');
                        ?>
                        <div class="list-item list-item-flex">
                            <span class="item-name"><?= htmlspecialchars($namaMenu) ?></span>
                            <span class="item-qty"><?= htmlspecialchars($item['qty']) ?> terjual</span>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="empty-state">Belum ada data penjualan.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Kolom Pesan Promo Terakhir -->
        <div class="staff-card list-card">
            <div class="card-header card-header-flex">
                <h3 class="header-title">Pesan Promo Terakhir</h3>
                <a href="<?= htmlspecialchars($urlPromoIndex) ?>" class="header-link">Lihat semua →</a>
            </div>
            <div class="list-body">
                <?php if (!empty($pesanTerakhir)): ?>
                    <?php foreach ($pesanTerakhir as $pesan): ?>
                        <?php
                            $judul = is_object($pesan) ? $pesan->judul : $pesan['judul'];
                            $waktu = (is_object($pesan) && method_exists($pesan->created_at, 'diffForHumans')) 
                                ? $pesan->created_at->diffForHumans() 
                                : ($pesan->created_at ?? $pesan['created_at']);
                        ?>
                        <div class="list-item">
                            <p class="item-title"><?= htmlspecialchars($judul) ?></p>
                            <p class="item-time"><?= htmlspecialchars($waktu) ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="empty-state">Belum ada pesan terkirim.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

</main>

</body>
</html>