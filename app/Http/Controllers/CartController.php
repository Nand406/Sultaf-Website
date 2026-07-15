<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    // Tampilkan isi keranjang (Checkout Step 1: Review)
    public function index(): View
    {
        $cart = $this->getCart();
        $menus = Menu::whereIn('id', array_keys($cart))->get()->keyBy('id');

        $items = collect($cart)->map(function ($qty, $menuId) use ($menus) {
            $menu = $menus->get($menuId);
            if (! $menu) return null;
            return [
                'menu'     => $menu,
                'qty'      => $qty,
                'subtotal' => $qty * (float) $menu->harga_makanan,
            ];
        })->filter()->values();

        $subtotal = $items->sum('subtotal');
        $tax      = round($subtotal * 0.10);
        $total    = $subtotal + $tax;

        return view('checkout.review', compact('items', 'subtotal', 'tax', 'total'));
    }

    // Tambah menu ke keranjang — menerima qty dari modal (default 1 jika tidak ada)
    public function add(Request $request, Menu $menu): RedirectResponse
    {
        $qty  = max(1, (int) $request->input('qty', 1));
        $cart = $this->getCart();
        $cart[$menu->id] = ($cart[$menu->id] ?? 0) + $qty;
        session(['cart' => $cart]);

        return back()->with('success', $menu->nama_makanan . ' (×' . $qty . ') ditambahkan ke keranjang.');
    }

    // Kurangi 1 dari item di keranjang
    public function decrease(Request $request, Menu $menu): RedirectResponse
    {
        $cart = $this->getCart();
        if (isset($cart[$menu->id])) {
            $cart[$menu->id]--;
            if ($cart[$menu->id] <= 0) {
                unset($cart[$menu->id]);
            }
        }
        session(['cart' => $cart]);

        return back();
    }

    // Hapus item dari keranjang
    public function remove(Menu $menu): RedirectResponse
    {
        $cart = $this->getCart();
        unset($cart[$menu->id]);
        session(['cart' => $cart]);

        return back();
    }

    protected function getCart(): array
    {
        return session('cart', []);
    }
}
