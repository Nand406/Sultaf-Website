<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    // 1. DIUBAH: Menambahkan Menu $menu untuk menangkap ID dari URL
    public function add(Request $request, Menu $menu): RedirectResponse
    {
        $qty = max(1, (int) $request->input('qty', 1));
        $cart = session()->get('cart', []);
        
        // DIUBAH: Gunakan $menu->id_menu sebagai key array session
        $menuId = $menu->id_menu;

        if (isset($cart[$menuId])) {
            $cart[$menuId] += $qty;
        } else {
            $cart[$menuId] = $qty;
        }

        session()->put('cart', $cart);

        return back()->with('success', 'Menu berhasil ditambahkan ke keranjang!');
    }

    public function index(): View
    {
        $cart = session()->get('cart', []);
        $cartIds = array_keys($cart);

        $menus = Menu::whereIn('id_menu', $cartIds)->get()->keyBy('id_menu');

        $items = collect();
        $subtotal = 0;

        foreach ($cart as $id => $qty) {
            if ($menus->has($id)) {
                $menu = $menus->get($id);
                $itemSubtotal = $menu->harga * $qty;
                $subtotal += $itemSubtotal;

                $items->push([
                    'menu' => $menu,
                    'qty' => $qty,
                    'subtotal' => $itemSubtotal
                ]);
            }
        }

        $tax = $subtotal * 0.10;
        $total = $subtotal + $tax;

        return view('checkout.review', compact('items', 'subtotal', 'tax', 'total'));
    }

    // 2. DIUBAH: $menu->id menjadi $menu->id_menu
    public function decrease(Menu $menu): RedirectResponse
    {
        $cart = $this->getCart();
        $menuId = $menu->id_menu; // <--- Perbaikan di sini
        
        if (isset($cart[$menuId])) {
            $cart[$menuId]--;
            if ($cart[$menuId] <= 0) {
                unset($cart[$menuId]);
            }
        }
        session(['cart' => $cart]);

        return back();
    }

    // 3. DIUBAH: $menu->id menjadi $menu->id_menu
    public function remove(Menu $menu): RedirectResponse
    {
        $cart = $this->getCart();
        $menuId = $menu->id_menu; // <--- Perbaikan di sini
        
        unset($cart[$menuId]);
        session(['cart' => $cart]);

        return back();
    }

    protected function getCart(): array
    {
        return session('cart', []);
    }
}