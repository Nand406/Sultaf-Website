<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promo;
use App\Models\Menu;
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
            // Memasukkan 'customer' karena user yang daftar lewat web mendapat role ini
            'total_member' => User::whereIn('role', ['member', 'customer'])->count(), 
            'promosi_aktif' => Promo::count(),
            'pesan_terkirim' => 0, 
        ];

        // Perbaikan query: Mengakomodasi status 'Lunas' dan menyaring menu yang sudah dihapus
        $menuTerlaris = TransaksiPenjualan::whereIn('status_pembayaran', ['terverifikasi', 'lunas', 'Lunas'])
            ->with('detail_transaksi.menu')
            ->get()
            ->pluck('detail_transaksi')
            ->flatten()
            ->groupBy('id_menu')
            ->map(function ($group) {
                // Ambil data pertama dari grup
                $firstDetail = $group->first();
                return [
                    'menu' => $firstDetail->menu ?? null, // Ambil relasi menu
                    'qty' => $group->sum('jumlah'),
                ];
            })
            ->filter(fn($item) => $item['menu'] !== null) // Saring: Buang jika menunya sudah dihapus di database
            ->sortByDesc('qty')
            ->take(5);

        $pesanTerakhir = collect();

        return view('admin.dashboard', compact('stats', 'menuTerlaris', 'pesanTerakhir'));
    }
}