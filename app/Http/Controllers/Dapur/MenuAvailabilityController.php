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
        // Ambil parameter kategori dan pencarian dari URL
        $category = $request->query('category');
        $search = $request->query('search'); // <-- TAMBAHAN

        // Filter menu berdasarkan kategori dan kata kunci pencarian
        $menus = Menu::when($category, function ($query, $category) {
                return $query->where('kategori', $category);
            })
            ->when($search, function ($query, $search) {
                return $query->where('nama_menu', 'like', '%' . $search . '%'); // <-- TAMBAHAN
            })
            ->orderBy('nama_menu')
            ->paginate(12)
            ->appends(['search' => $search, 'category' => $category]); // Agar pagination tidak hilang saat dicari

        // Hitung total untuk badge (berdasarkan semua menu)
        $totalHabis = Menu::where('status_ketersediaan', 'habis')->count();
        $totalTersedia = Menu::where('status_ketersediaan', 'tersedia')->count();

        return view('dapur.menu.index', compact('menus', 'totalHabis', 'totalTersedia', 'category'));
    }

    public function toggle(Menu $menu): RedirectResponse
    {
        $newStatus = $menu->status_ketersediaan === 'tersedia' ? 'habis' : 'tersedia';
        $menu->update(['status_ketersediaan' => $newStatus]);

        return back()->with('success', "Status menu '{$menu->nama_menu}' berhasil diubah menjadi {$newStatus}.");
    }
}