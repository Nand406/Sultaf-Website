<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Menu;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MenuManagementController extends Controller
{
    // Tampilkan semua menu (setara getAllMenu() di Diagram Kelas laporan)
    public function index(Request $request): View
    {
        $menus = Menu::with('category')
            ->when($request->search, fn ($q) => $q->where('nama_makanan', 'like', '%' . $request->search . '%'))
            ->orderBy('nama_makanan')
            ->paginate(10)
            ->withQueryString();

        return view('admin.menu.index', compact('menus'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('order')->get();
        return view('admin.menu.create', compact('categories'));
    }

    // setara insertMenu(): boolean di Diagram Kelas
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateMenu($request);

        if ($request->hasFile('foto')) {
            $validated['foto_makanan'] = $request->file('foto')->store('menu-images', 'public');
        }

        Menu::create($validated);

        return redirect()->route('admin.menu.index')->with('success', 'Menu baru berhasil ditambahkan.');
    }

    public function edit(Menu $menu): View
    {
        $categories = Category::orderBy('order')->get();
        return view('admin.menu.edit', compact('menu', 'categories'));
    }

    // setara updateMenu(): boolean di Diagram Kelas
    public function update(Request $request, Menu $menu): RedirectResponse
    {
        $validated = $this->validateMenu($request, $menu->id);

        if ($request->hasFile('foto')) {
            if ($menu->foto_makanan) {
                Storage::disk('public')->delete($menu->foto_makanan);
            }
            $validated['foto_makanan'] = $request->file('foto')->store('menu-images', 'public');
        }

        $menu->update($validated);

        return redirect()->route('admin.menu.index')->with('success', 'Menu "' . $menu->nama_makanan . '" berhasil diperbarui.');
    }

    // setara deleteMenu(): boolean di Diagram Kelas
    public function destroy(Menu $menu): RedirectResponse
    {
        if ($menu->foto_makanan) {
            Storage::disk('public')->delete($menu->foto_makanan);
        }

        $nama = $menu->nama_makanan;
        $menu->delete();

        return back()->with('success', 'Menu "' . $nama . '" berhasil dihapus.');
    }

    // Toggle cepat status habis/tersedia langsung dari tabel
    public function toggleHabis(Menu $menu): RedirectResponse
    {
        $menu->update(['habis' => ! $menu->habis]);

        return back()->with('success', 'Status menu "' . $menu->nama_makanan . '" diperbarui.');
    }

    protected function validateMenu(Request $request, ?int $ignoreId = null): array
    {
        $validated = $request->validate([
            'category_id'   => ['required', 'exists:categories,id'],
            'nama_makanan'  => ['required', 'string', 'max:255'],
            'deskripsi'     => ['nullable', 'string', 'max:1000'],
            'harga_makanan' => ['required', 'numeric', 'min:0'],
            'harga_modal'   => ['nullable', 'numeric', 'min:0'],
            'rating'        => ['nullable', 'numeric', 'min:0', 'max:5'],
            'spice_level'   => ['required', 'integer', 'min:0', 'max:3'],
            'foto'          => ['nullable', 'image', 'max:2048'],
        ]);

        // Checkbox tidak terkirim sama sekali kalau tidak dicentang, jadi diisi manual
        $validated['is_halal']    = $request->boolean('is_halal');
        $validated['habis']       = $request->boolean('habis');
        $validated['harga_modal'] = $validated['harga_modal'] ?? 0;

        return $validated;
    }
}
