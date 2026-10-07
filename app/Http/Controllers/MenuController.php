<?php

namespace App\Http\Controllers;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuController extends Controller
{
    // Use Case: Lihat Menu — getAllMenu(), bisa difilter per kategori
    public function index()
{
    // Mengambil semua data menu dari database
    $menus = Menu::all();

    // Mengirim data menu ke tampilan halaman (view)
    return view('menu.index', compact('menus'));
}
}
