<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\DetailTransaksi;
use App\Models\TransaksiPenjualan;
use App\Services\MemberPointService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PosController extends Controller
{
    public function __construct(protected MemberPointService $memberPoints) {}

    public function index(Request $request): View
    {
        // Category sudah tidak ada di ERD baru, langsung ambil semua menu yang tersedia
        $menus = Menu::where('status_ketersediaan', 'tersedia')
            ->orderBy('nama_menu') // DIUBAH: 'nama_makanan' -> 'nama_menu'
            ->get();

        // Variabel dummy agar View tidak error jika masih memanggil $categories
        $categories = collect();
        $activeCategory = null;

        $cart = session('pos_cart', []);
        // DIUBAH: primary key Menu adalah 'id_menu', bukan 'id'
        $cartMenus = Menu::whereIn('id_menu', array_keys($cart))->get()->keyBy('id_menu');

        $cartItems = collect($cart)->map(function ($qty, $menuId) use ($cartMenus) {
            $menu = $cartMenus->get($menuId);
            if (! $menu) return null;
            return [
                'menu' => $menu, 
                'qty' => $qty, 
                'subtotal' => $qty * (float) $menu->harga // DIUBAH: 'harga_makanan' -> 'harga'
            ];
        })->filter()->values();

        $subtotal = $cartItems->sum('subtotal');
        $tax = round($subtotal * 0.10);
        $total = $subtotal + $tax;

        return view('kasir.pos.index', compact(
            'categories', 'activeCategory', 'menus', 'cartItems', 'subtotal', 'tax', 'total'
        ));
    }

    public function addItem(Request $request, Menu $menu): RedirectResponse
    {
        $qty = max(1, (int) $request->input('qty', 1));
        $cart = session('pos_cart', []);
        // DIUBAH: 'id' -> 'id_menu'
        $cart[$menu->id_menu] = ($cart[$menu->id_menu] ?? 0) + $qty;
        session(['pos_cart' => $cart]);

        return back();
    }

    public function decreaseItem(Menu $menu): RedirectResponse
    {
        $cart = session('pos_cart', []);
        // DIUBAH: 'id' -> 'id_menu'
        if (isset($cart[$menu->id_menu])) {
            $cart[$menu->id_menu]--;
            if ($cart[$menu->id_menu] <= 0) unset($cart[$menu->id_menu]);
        }
        session(['pos_cart' => $cart]);

        return back();
    }

    public function removeItem(Menu $menu): RedirectResponse
    {
        $cart = session('pos_cart', []);
        // DIUBAH: 'id' -> 'id_menu'
        unset($cart[$menu->id_menu]);
        session(['pos_cart' => $cart]);

        return back();
    }

    public function clearCart(): RedirectResponse
    {
        session()->forget('pos_cart');
        return back();
    }

    public function checkout(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tipe_pesanan'      => ['required', 'in:dine_in,takeaway'],
            'nomor_meja'        => ['required_if:tipe_pesanan,dine_in', 'nullable', 'integer', 'min:1', 'max:20'],
            'metode_pembayaran' => ['required', 'in:cash,gopay,ovo,dana,qris,card'],
            'nama_pelanggan'    => ['nullable', 'string', 'max:255'],
            'no_telepon'        => ['nullable', 'string', 'max:20'],
        ]);

        $cart = session('pos_cart', []);

        if (empty($cart)) {
            return back()->with('error', 'Keranjang POS masih kosong.');
        }

        // DIUBAH: 'id' -> 'id_menu', 'harga_makanan' -> 'harga'
        $menus = Menu::whereIn('id_menu', array_keys($cart))->get()->keyBy('id_menu');
        $subtotal = collect($cart)->map(fn ($qty, $id) => $qty * (float) $menus[$id]->harga)->sum();
        $tax = round($subtotal * 0.10);
        $total = $subtotal + $tax;

        // DIUBAH: Sesuaikan dengan kolom yang ada di migration baru
        $transaksi = TransaksiPenjualan::create([
            'id_user'           => null, // Walk-in customer
            'id_promo'          => null,
            'tipe_pesanan'      => $validated['tipe_pesanan'],
            'no_meja'           => $validated['nomor_meja'] ?? null,
            'total_bayar'       => $total,
            'uang_bayar'        => null,
            'diskon'            => 0,
            'status_pesanan'    => 'pending',
            'status_pembayaran' => 'terverifikasi', // Kasir langsung verifikasi
            'metode_pembayaran' => $validated['metode_pembayaran'],
            'catatan'           => 'Walk-in customer',
        ]);

        foreach ($cart as $menuId => $qty) {
            // DIUBAH: TransaksiItem -> DetailTransaksi, dan nama kolomnya
            DetailTransaksi::create([
                'id_transaksi' => $transaksi->id_transaksi, // DIUBAH: 'id' -> 'id_transaksi'
                'id_menu'      => $menuId,                  // DIUBAH: 'menu_id' -> 'id_menu'
                'jumlah'       => $qty,                     // DIUBAH: 'qty' -> 'jumlah'
                'subtotal'     => $qty * (float) $menus[$menuId]->harga,
                'harga_satuan' => $menus[$menuId]->harga,
            ]);
        }

        session()->forget('pos_cart');

        // Verifikasi nomor HP untuk poin member
        $member = $this->memberPoints->awardFromTransaksi($transaksi, $validated['no_telepon'] ?? null);

        // DIUBAH: kode_transaksi dihapus, pakai format ID baru
        $kodeTransaksi = '#SLT-' . str_pad($transaksi->id_transaksi, 5, '0', STR_PAD_LEFT);
        $flash = "Transaksi {$kodeTransaksi} berhasil disimpan.";
        
        if (! empty($validated['no_telepon'])) {
            $flash .= $member
                ? " Nomor terdaftar sebagai member ({$member->user->username}) — poin berhasil ditambahkan."
                : ' Nomor HP tidak terdaftar sebagai member.';
        }

        return redirect()->route('kasir.pos.receipt', $transaksi)->with('success', $flash);
    }

    public function receipt(TransaksiPenjualan $transaksi): View
    {
        // DIUBAH: 'items.menu' -> 'detail_transaksi.menu'
        $transaksi->load(['detail_transaksi.menu', 'user']);
        return view('kasir.pos.receipt', compact('transaksi'));
    }
}