<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    // Tampilkan isi keranjang (Checkout Step 1: Review)
    public function add(Request $request)
    {
        // 1. Tangkap ID menggunakan id_menu dari form HTML
        $id = $request->id_menu; 
        $qty = $request->qty ?? 1;

        // Jika ID kosong, kembalikan error
        if (!$id) {
            return back()->with('error', 'Gagal menambahkan: ID Menu tidak ditemukan.');
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id] += $qty;
        } else {
            $cart[$id] = $qty;
        }

        session()->put('cart', $cart);

        return back()->with('success', 'Menu berhasil ditambahkan ke keranjang!');
    }

    public function index()
    {
        $cart = session()->get('cart', []);
        $cartIds = array_keys($cart);

        // 2. WAJIB MENGGUNAKAN id_menu DI SINI
        $menus = \App\Models\Menu::whereIn('id_menu', $cartIds)->get()->keyBy('id_menu');

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
