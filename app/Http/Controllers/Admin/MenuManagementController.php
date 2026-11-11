<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuManagementController extends Controller
{
    public function index(): View
{
    // Gunakan paginate(10) untuk membatasi 10 data per halaman
    $menus = Menu::orderBy('nama_menu')->paginate(10); 
    return view('admin.menu.index', compact('menus'));
}

    public function create(): View
    {
        // Category sudah tidak ada di ERD baru.
        // Kita kirim koleksi kosong agar View tidak error jika masih memanggil $categories.
        $categories = collect(); 
        return view('admin.menu.create', compact('categories'));
    }

        public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_menu' => ['required', 'string', 'max:255'],
            'harga' => ['required', 'numeric', 'min:0'],
            'foto_menu' => ['nullable', 'image', 'max:2048'],
            'status_ketersediaan' => ['required', 'in:tersedia,habis'],
        ]);

        // --- BAGIAN YANG DIUBAH ---
        if ($request->hasFile('foto_menu')) {
            $file = $request->file('foto_menu');
            // Buat nama unik untuk file
            $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            // Pindahkan langsung ke folder public/menu-images
            $file->move(public_path('menu-images'), $filename);
            
            $validated['foto_menu'] = 'menu-images/' . $filename;
        }
        // --------------------------

        Menu::create($validated);

        return redirect()->route('admin.menu.index')->with('success', 'Menu berhasil ditambahkan.');
    }

    public function edit(Menu $menu): View
    {
        // Sama seperti create, kita kirim koleksi kosong.
        $categories = collect();
        return view('admin.menu.edit', compact('menu', 'categories'));
    }

        public function update(Request $request, Menu $menu): RedirectResponse
    {
                $validated = $request->validate([
            'nama_menu' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'in:makanan,minuman,camilan'], // <--- Tambah ini
            'harga' => ['required', 'numeric', 'min:0'],
            'foto_menu' => ['nullable', 'image', 'max:2048'],
            'status_ketersediaan' => ['required', 'in:tersedia,habis'],
        ]);

        // --- BAGIAN YANG DIUBAH ---
        if ($request->hasFile('foto_menu')) {
            // Hapus foto lama jika ada
            if ($menu->foto_menu && file_exists(public_path($menu->foto_menu))) {
                unlink(public_path($menu->foto_menu));
            }

            $file = $request->file('foto_menu');
            $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $file->move(public_path('menu-images'), $filename);
            
            $validated['foto_menu'] = 'menu-images/' . $filename;
        }
        // --------------------------

        $menu->update($validated);

        return redirect()->route('admin.menu.index')->with('success', 'Menu berhasil diperbarui.');
    }

    public function destroy(Menu $menu): RedirectResponse
    {
        $menu->delete();
        return redirect()->route('admin.menu.index')->with('success', 'Menu berhasil dihapus.');
    }
}