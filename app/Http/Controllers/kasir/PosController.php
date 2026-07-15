<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Menu;
use App\Models\TransaksiItem;
use App\Models\TransaksiPenjualan;
use App\Services\MemberPointService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PosController extends Controller
{
    public function __construct(protected MemberPointService $memberPoints) {}

    public function index(Request $request): View
    {
        $categories = Category::orderBy('order')->get();
        $activeCategory = $request->get('category', $categories->first()?->slug);

        $menus = Menu::tersedia()
            ->when($activeCategory, fn ($q) => $q->whereHas('category', fn ($q2) => $q2->where('slug', $activeCategory)))
            ->orderBy('nama_makanan')
            ->get();

        $cart = session('pos_cart', []);
        $cartMenus = Menu::whereIn('id', array_keys($cart))->get()->keyBy('id');

        $cartItems = collect($cart)->map(function ($qty, $menuId) use ($cartMenus) {
            $menu = $cartMenus->get($menuId);
            if (! $menu) return null;
            return ['menu' => $menu, 'qty' => $qty, 'subtotal' => $qty * (float) $menu->harga_makanan];
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
        $cart[$menu->id] = ($cart[$menu->id] ?? 0) + $qty;
        session(['pos_cart' => $cart]);

        return back();
    }

    public function decreaseItem(Menu $menu): RedirectResponse
    {
        $cart = session('pos_cart', []);
        if (isset($cart[$menu->id])) {
            $cart[$menu->id]--;
            if ($cart[$menu->id] <= 0) unset($cart[$menu->id]);
        }
        session(['pos_cart' => $cart]);

        return back();
    }

    public function removeItem(Menu $menu): RedirectResponse
    {
        $cart = session('pos_cart', []);
        unset($cart[$menu->id]);
        session(['pos_cart' => $cart]);

        return back();
    }

    public function clearCart(): RedirectResponse
    {
        session()->forget('pos_cart');
        return back();
    }

    // Proses pembayaran langsung di kasir -> otomatis terverifikasi + cek member via no HP
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

        $menus = Menu::whereIn('id', array_keys($cart))->get()->keyBy('id');
        $subtotal = collect($cart)->map(fn ($qty, $id) => $qty * (float) $menus[$id]->harga_makanan)->sum();
        $tax = round($subtotal * 0.10);
        $total = $subtotal + $tax;

        $transaksi = TransaksiPenjualan::create([
            'kode_transaksi'    => 'POS-' . strtoupper(Str::random(6)),
            'user_id'           => null,
            'nama_pelanggan'    => $validated['nama_pelanggan'] ?? null,
            'no_telepon'        => $validated['no_telepon'] ?? null,
            'tipe_pesanan'      => $validated['tipe_pesanan'],
            'nomor_meja'        => $validated['nomor_meja'] ?? null,
            'subtotal'          => $subtotal,
            'pajak'             => $tax,
            'service_charge'    => 0,
            'diskon_member'     => 0,
            'total_harga'       => $total,
            // Transaksi kasir langsung: dibayar di depan, jadi otomatis terverifikasi
            'status_pesanan'    => 'pending',
            'status_pembayaran' => 'terverifikasi',
            'metode_pembayaran' => $validated['metode_pembayaran'],
            'catatan'           => 'Walk-in customer',
            'tgl_transaksi'     => now(),
        ]);

        foreach ($cart as $menuId => $qty) {
            TransaksiItem::create([
                'transaksi_penjualan_id' => $transaksi->id,
                'menu_id'                => $menuId,
                'qty'                    => $qty,
                'harga_satuan'           => $menus[$menuId]->harga_makanan,
            ]);
        }

        session()->forget('pos_cart');

        // Verifikasi nomor HP: kalau terdaftar sebagai member, poin langsung ditambahkan
        $member = $this->memberPoints->awardFromTransaksi($transaksi, $validated['no_telepon'] ?? null);

        $flash = "Transaksi #{$transaksi->kode_transaksi} berhasil disimpan.";
        if (! empty($validated['no_telepon'])) {
            $flash .= $member
                ? " Nomor terdaftar sebagai member ({$member->name}) — poin berhasil ditambahkan."
                : ' Nomor HP tidak terdaftar sebagai member.';
        }

        return redirect()->route('kasir.pos.receipt', $transaksi)->with('success', $flash);
    }

    public function receipt(TransaksiPenjualan $transaksi): View
    {
        $transaksi->load(['items.menu', 'user']);
        return view('kasir.pos.receipt', compact('transaksi'));
    }
}
