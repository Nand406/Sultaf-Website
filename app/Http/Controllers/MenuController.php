<?php

namespace App\Http\Controllers;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuController extends Controller
{
    // Use Case: Lihat Menu — getAllMenu(), bisa difilter per kategori
        public function index(Request $request): View
    {
        // Ambil parameter 'category' dari URL
        $category = $request->query('category');

        // Query menu berdasarkan kategori yang dipilih
        $menus = Menu::where('status_ketersediaan', 'tersedia')
            ->when($category, function ($query, $category) {
                return $query->where('kategori', $category);
            })
            ->get();

        return view('menu.index', compact('menus', 'category'));
    }
}
