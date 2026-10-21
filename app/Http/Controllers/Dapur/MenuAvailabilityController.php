<?php

namespace App\Http\Controllers\Dapur;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuAvailabilityController extends Controller
{
        public function index(Request $request): View
    {
        // Ambil semua menu dengan pagination
        $menus = Menu::orderBy('nama_menu')->paginate(10);

        // Statistik untuk header
        $totalHabis = Menu::where('status_ketersediaan', 'habis')->count();
        $totalTersedia = Menu::where('status_ketersediaan', 'tersedia')->count(); // <--- TAMBAHKAN BARIS INI

        return view('dapur.menu.index', compact('menus', 'totalHabis', 'totalTersedia'));
    }

    public function toggle(Menu $menu): RedirectResponse
    {
        // Ubah status: jika 'tersedia' jadi 'habis', dan sebaliknya
        $newStatus = $menu->status_ketersediaan === 'tersedia' ? 'habis' : 'tersedia';
        
        $menu->update(['status_ketersediaan' => $newStatus]);

        return back()->with('success', "Status menu '{$menu->nama_menu}' berhasil diubah menjadi {$newStatus}.");
    }
}