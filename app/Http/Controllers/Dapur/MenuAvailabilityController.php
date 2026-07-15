<?php

namespace App\Http\Controllers\Dapur;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Menu;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuAvailabilityController extends Controller
{
    // Daftar semua menu, dikelompokkan per kategori, khusus untuk toggle ketersediaan
    public function index(Request $request): View
    {
        $categories = Category::orderBy('order')->get();
        $activeCategory = $request->get('category', $categories->first()?->slug);

        $menus = Menu::with('category')
            ->when($activeCategory, fn ($q) => $q->whereHas('category', fn ($q2) => $q2->where('slug', $activeCategory)))
            ->orderBy('nama_makanan')
            ->get();

        $totalHabis = Menu::where('habis', true)->count();

        return view('dapur.menu.index', compact('categories', 'activeCategory', 'menus', 'totalHabis'));
    }

    // Dapur menandai menu tersedia / tidak tersedia (field 'habis' pada tabel menus)
    public function toggle(Menu $menu): RedirectResponse
    {
        $menu->update(['habis' => ! $menu->habis]);

        $status = $menu->habis ? 'tidak tersedia' : 'tersedia';

        return back()->with('success', "Menu \"{$menu->nama_makanan}\" ditandai {$status}.");
    }
}
