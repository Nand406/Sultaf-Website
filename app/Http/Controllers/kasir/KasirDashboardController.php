<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\TransaksiPenjualan;
use Illuminate\View\View;

class KasirDashboardController extends Controller
{
    public function index(): View
    {
        $today = now()->toDateString();

        $stats = [
            'menunggu'    => TransaksiPenjualan::where('status_pembayaran', 'menunggu')->count(),
            'terverifikasi_hari_ini' => TransaksiPenjualan::where('status_pembayaran', 'terverifikasi')
                ->whereDate('updated_at', $today)->count(),
            // 'total_hari_ini' (omzet) SENGAJA dihapus — laporan keuangan/omset
            // hanya boleh dilihat oleh Owner, bukan Kasir.
            'pesanan_aktif' => TransaksiPenjualan::whereIn('status_pesanan', ['pending', 'cooking', 'ready', 'diantar'])->count(),
        ];

        $recentOrders = TransaksiPenjualan::with('user')
            ->latest('tgl_transaksi')
            ->limit(8)
            ->get();

        return view('kasir.dashboard', compact('stats', 'recentOrders'));
    }
}
