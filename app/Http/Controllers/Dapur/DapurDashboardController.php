<?php

namespace App\Http\Controllers\Dapur;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\TransaksiPenjualan;
use Illuminate\View\View;

class DapurDashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'pending'    => TransaksiPenjualan::where('status_pesanan', 'pending')
                ->where('status_pembayaran', 'terverifikasi')->count(),
            'cooking'    => TransaksiPenjualan::where('status_pesanan', 'cooking')->count(),
            'menu_habis' => Menu::where('habis', true)->count(),
            'total_menu' => Menu::count(),
        ];

        return view('dapur.dashboard', compact('stats'));
    }
}
