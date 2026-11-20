<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\DetailTransaksi;
use App\Models\TransaksiPenjualan;
use App\Models\User;
use App\Models\Promo;
use App\Services\MemberPointService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PosController extends Controller
{
    public function __construct(protected MemberPointService $memberPoints) {}

    public function index(Request $request): View
    {
        $category = $request->query('category');

        $menus = Menu::where('status_ketersediaan', 'tersedia')
            ->when($category, function ($query, $category) {
                return $query->where('kategori', $category);
            })
            ->orderBy('nama_menu')
            ->get();

        $cart = session('pos_cart', []);
        $cartMenus = Menu::whereIn('id_menu', array_keys($cart))->get()->keyBy('id_menu');

        $cartItems = collect($cart)->map(function ($qty, $menuId) use ($cartMenus) {
            $menu = $cartMenus->get($menuId);
            if (! $menu) return null;
            return [
                'menu' => $menu, 
                'qty' => $qty, 
                'subtotal' => $qty * (float) $menu->harga
            ];
        })->filter()->values();

        $subtotal = $cartItems->sum('subtotal');
        $tax = round($subtotal * 0.10);
        $total = $subtotal + $tax;

        return view('kasir.pos.index', compact(
            'menus', 'cartItems', 'subtotal', 'tax', 'total', 'category'
        ));
    }

    public function addItem(Request $request, Menu $menu): RedirectResponse
    {
        $qty = max(1, (int) $request->input('qty', 1));
        $cart = session('pos_cart', []);
        $cart[$menu->id_menu] = ($cart[$menu->id_menu] ?? 0) + $qty;
        session(['pos_cart' => $cart]);

        return back();
    }

    public function decreaseItem(Menu $menu): RedirectResponse
    {
        $cart = session('pos_cart', []);
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
        unset($cart[$menu->id_menu]);
        session(['pos_cart' => $cart]);

        return back();
    }

    public function clearCart(): RedirectResponse
    {
        session()->forget('pos_cart');
        return back();
    }

    // ================= FUNGSI CEK MEMBER UNTUK AJAX =================
    public function checkMember(Request $request)
    {
        $noTelepon = $request->input('no_telepon');
        if (empty($noTelepon)) {
            return response()->json(['diskon' => 0, 'total_poin' => 0, 'promo' => null]);
        }

        $digits = preg_replace('/\D/', '', $noTelepon);
        if (str_starts_with($digits, '62')) {
            $digits = '0' . substr($digits, 2);
        }

        $user = User::where('no_hp', 'like', "%$digits%")->first();
        
        if (!$user) {
            return response()->json(['diskon' => 0, 'total_poin' => 0, 'promo' => null]);
        }

        // Hitung total poin real-time
        $totalPoin = TransaksiPenjualan::where('id_user', $user->id_user)
            ->whereIn('status_pembayaran', ['terverifikasi', 'lunas'])
            ->get()
            ->sum(function ($trx) {
                return floor($trx->total_bayar / 1000);
            });

        // ================= TAMBAHAN: Cek Promo yang sudah dipakai =================
        $usedPromoIds = TransaksiPenjualan::where('id_user', $user->id_user)
            ->whereNotNull('id_promo')
            ->pluck('id_promo')
            ->unique()
            ->toArray();

        // Cari promo tertinggi yang tercapai DAN BELUM DIPAKAI
        $promo = Promo::where('minimal_poin', '<=', $totalPoin)
            ->whereNotIn('id_promo', $usedPromoIds) // <--- TAMBAHKAN INI
            ->orderByDesc('minimal_poin')
            ->first();
        // =========================================================================

        return response()->json([
            'diskon' => $promo ? $promo->potongan_harga : 0,
            'total_poin' => $totalPoin,
            'promo' => $promo ? $promo->nama_promo : null,
            'nama_pelanggan' => $user->username
        ]);
    }

    // ================= FUNGSI CHECKOUT =================
    public function checkout(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tipe_pesanan'      => ['required', 'in:dine_in,takeaway'],
            'nomor_meja'        => ['required_if:tipe_pesanan,dine_in', 'nullable', 'integer', 'min:1', 'max:20'],
            'metode_pembayaran' => ['required', 'in:cash,qris'], 
            'nama_pelanggan'    => ['nullable', 'string', 'max:255'],
            'no_telepon'        => ['nullable', 'string', 'max:20'],
            'uang_bayar'        => ['nullable', 'numeric', 'min:0'], 
        ]);

        $cart = session('pos_cart', []);

        if (empty($cart)) {
            return back()->with('error', 'Keranjang POS masih kosong.');
        }

        $menus = Menu::whereIn('id_menu', array_keys($cart))->get()->keyBy('id_menu');
        $subtotal = collect($cart)->map(fn ($qty, $id) => $qty * (float) $menus[$id]->harga)->sum();
        $tax = round($subtotal * 0.10);

        // Cek Member & Promo
        $userId = null;
        $diskon = 0;
        $promoId = null;

        if (!empty($validated['no_telepon'])) {
            $digits = preg_replace('/\D/', '', $validated['no_telepon']);
            if (str_starts_with($digits, '62')) {
                $digits = '0' . substr($digits, 2);
            }
            $user = User::where('no_hp', 'like', "%$digits%")->first();
            
            if ($user) {
                $userId = $user->id_user;
                
                $totalPoin = TransaksiPenjualan::where('id_user', $user->id_user)
                    ->whereIn('status_pembayaran', ['terverifikasi', 'lunas'])
                    ->get()
                    ->sum(fn($trx) => floor($trx->total_bayar / 1000));

                // ================= TAMBAHAN: Cek Promo yang sudah dipakai =================
                $usedPromoIds = TransaksiPenjualan::where('id_user', $user->id_user)
                    ->whereNotNull('id_promo')
                    ->pluck('id_promo')
                    ->unique()
                    ->toArray();

                // Cari promo baru (belum dipakai)
                $promo = Promo::where('minimal_poin', '<=', $totalPoin)
                    ->whereNotIn('id_promo', $usedPromoIds) // <--- TAMBAHKAN INI
                    ->orderByDesc('minimal_poin')
                    ->first();
                // =========================================================================
                    
                if ($promo) {
                    $diskon = $promo->potongan_harga;
                    $promoId = $promo->id_promo;
                }
            }
        }

        $total = max(0, $subtotal + $tax - $diskon);

        // Tentukan Uang Bayar
        $uangBayar = $total;
        if ($validated['metode_pembayaran'] === 'cash') {
            if (empty($validated['uang_bayar']) || $validated['uang_bayar'] < $total) {
                return back()->with('error', 'Uang tunai yang dimasukkan kurang dari total tagihan.');
            }
            $uangBayar = $validated['uang_bayar'];
        }

        $transaksi = TransaksiPenjualan::create([
            'id_user'           => $userId,
            'id_promo'          => $promoId,
            'tipe_pesanan'      => $validated['tipe_pesanan'],
            'no_meja'           => $validated['nomor_meja'] ?? null,
            'total_bayar'       => $total,
            'uang_bayar'        => $uangBayar,
            'diskon'            => $diskon,
            'status_pesanan'    => 'pending',
            'status_pembayaran' => 'terverifikasi',
            'metode_pembayaran' => $validated['metode_pembayaran'],
        ]);

        foreach ($cart as $menuId => $qty) {
            DetailTransaksi::create([
                'id_transaksi' => $transaksi->id_transaksi,
                'id_menu'      => $menuId,
                'jumlah'       => $qty,
                'subtotal'     => $qty * (float) $menus[$menuId]->harga,
                'harga_satuan' => $menus[$menuId]->harga,
            ]);
        }

        session()->forget('pos_cart');

        $memberUser = $this->memberPoints->awardFromTransaksi($transaksi);

        $kodeTransaksi = '#SLT-' . str_pad($transaksi->id_transaksi, 5, '0', STR_PAD_LEFT);
        $flash = "Transaksi {$kodeTransaksi} berhasil disimpan.";
        
        if ($memberUser) {
            $flash .= " Poin berhasil ditambahkan untuk member ({$memberUser->username}).";
        } elseif (!empty($validated['no_telepon'])) {
            $flash .= " Nomor HP tidak terdaftar sebagai member.";
        }

        return redirect()->route('kasir.pos.receipt', $transaksi)->with('success', $flash);
    }

    public function receipt(TransaksiPenjualan $transaksi): View
    {
        $transaksi->load(['detail_transaksi.menu', 'user']);
        return view('kasir.pos.receipt', compact('transaksi'));
    }
}