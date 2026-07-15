<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Benefit;
use App\Models\Menu;
use App\Models\PromoMessage;
use App\Models\TransaksiPenjualan;
use App\Models\User;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_menu'    => Menu::count(),
            'menu_habis'    => Menu::where('habis', true)->count(),
            'total_member'  => User::where('role', 'member')->count(),
            // 'omzet_bulan_ini' SENGAJA dihapus — laporan keuangan/omset
            // hanya boleh dilihat oleh Owner, bukan Admin.
            'promosi_aktif' => Benefit::where('aktif', true)->count(),
            'pesan_terkirim' => PromoMessage::count(),
        ];

        $menuTerlaris = TransaksiPenjualan::where('status_pembayaran', 'terverifikasi')
            ->with('items.menu')
            ->get()
            ->pluck('items')
            ->flatten()
            ->groupBy('menu_id')
            ->map(fn ($group) => [
                'menu' => $group->first()->menu,
                'qty'  => $group->sum('qty'),
            ])
            ->sortByDesc('qty')
            ->take(5)
            ->values();

        $pesanTerakhir = PromoMessage::with('pengirim')->latest()->limit(3)->get();

        return view('admin.dashboard', compact('stats', 'menuTerlaris', 'pesanTerakhir'));
    }
}
