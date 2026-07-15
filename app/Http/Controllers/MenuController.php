<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuController extends Controller
{
    // Use Case: Lihat Menu — getAllMenu(), bisa difilter per kategori
    public function index(Request $request): View
    {
        $categories = Category::orderBy('order')->get();

        $activeCategory = $request->query('category', $categories->first()->slug ?? null);

        $menus = Menu::with('category')
            ->when($activeCategory, function ($query) use ($activeCategory) {
                $query->whereHas('category', fn ($q) => $q->where('slug', $activeCategory));
            })
            ->orderByDesc('rating')
            ->get();

        return view('menu.index', [
            'categories' => $categories,
            'activeCategory' => $activeCategory,
            'menus' => $menus,
        ]);
    }
}
