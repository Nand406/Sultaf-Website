<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promo;
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
            'total_menu' => Menu::count(),
            'menu_habis' => Menu::where('status_ketersediaan', 'habis')->count(),
            'total_member' => User::where('role', 'member')->count(),
            'promosi_aktif' => Promo::count(),
            'pesan_terkirim' => 0, // <--- Tambahkan ini agar View tidak error
        ];

        $menuTerlaris = TransaksiPenjualan::where('status_pembayaran', 'terverifikasi')
            ->with('detail_transaksi.menu')           // DIUBAH: 'items.menu' -> 'detail_transaksi.menu'
            ->get()
            ->pluck('detail_transaksi')               // DIUBAH: 'items' -> 'detail_transaksi'
            ->flatten()
            ->groupBy('id_menu')                      // DIUBAH: 'menu_id' -> 'id_menu'
            ->map(fn($group) => [
                'menu' => $group->first()->menu,
                'qty' => $group->sum('jumlah'),      // DIUBAH: 'qty' -> 'jumlah'
            ])
            ->sortByDesc('qty')
            ->take(5);

        $pesanTerakhir = collect();

        return view('admin.dashboard', compact('stats', 'menuTerlaris', 'pesanTerakhir'));
    }
}
