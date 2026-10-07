<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use App\Models\Menu;
use App\Models\DetailTransaksi;
use App\Models\TransaksiPenjualan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    /* ===================== STEP 2 — DETAILS ===================== */

    public function details(): View
    {
        $this->ensureCartNotEmpty();
        return view('checkout.details');
    }

    public function storeDetails(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tipe_pesanan' => ['required', 'in:dine_in,takeaway'],
            'nomor_meja' => ['required_if:tipe_pesanan,dine_in', 'nullable', 'integer', 'min:1', 'max:20'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ]);

        session(['checkout.details' => $validated]);
        return redirect()->route('checkout.payment');
    }

    /* ===================== STEP 3 — PAYMENT ===================== */

    public function payment(): View
    {
        $this->ensureCartNotEmpty();
        [, $subtotal, $tax, $diskon, $total] = $this->calculateTotals();
        return view('checkout.payment', compact('subtotal', 'tax', 'diskon', 'total'));
    }

    public function storePayment(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'metode_pembayaran' => ['required', 'in:gopay,ovo,dana,qris,cash'],
            'bukti_pembayaran' => ['nullable', 'image', 'max:5120'],
            'nama_pelanggan' => ['required', 'string', 'max:255'],
            'no_telepon' => ['required', 'string', 'max:20'],
        ]);

        if ($request->hasFile('bukti_pembayaran')) {
            $validated['bukti_pembayaran'] = $request->file('bukti_pembayaran')
                ->store('bukti-pembayaran', 'public');
        }

        session(['checkout.payment' => $validated]);
        return $this->complete();
    }

    /* ===================== STEP 4 — FINISH ===================== */

        protected function complete(): RedirectResponse
    {
        [$items, $subtotal, $tax, $diskon, $total] = $this->calculateTotals();
        $details = session('checkout.details', []);
        $payment = session('checkout.payment', []);

        $transaksi = TransaksiPenjualan::create([
            'id_user' => Auth::id(),
            'id_promo' => null, // Bisa diisi jika user memilih promo tertentu
            'tipe_pesanan' => $details['tipe_pesanan'] ?? 'takeaway',
            'no_meja' => $details['nomor_meja'] ?? null,
            'total_bayar' => $total,
            'diskon' => $diskon,
            'status_pesanan' => 'pending',
            'status_pembayaran' => 'menunggu',
            'metode_pembayaran' => $payment['metode_pembayaran'] ?? null,
        ]);

        foreach ($items as $item) {
            DetailTransaksi::create([
                'id_transaksi' => $transaksi->id_transaksi,
                'id_menu' => $item['menu']->id_menu,
                'jumlah' => $item['qty'],
                'subtotal' => $item['menu']->harga * $item['qty'],
                'harga_satuan' => $item['menu']->harga,
            ]);
        }

        session()->forget(['cart', 'checkout.details', 'checkout.payment']);
        return redirect()->route('checkout.finish', $transaksi);
    }

    public function finish(TransaksiPenjualan $transaksi): View
    {
        $transaksi->load('detail_transaksi.menu');
        return view('checkout.finish', compact('transaksi'));
    }

    /* ===================== Helper ===================== */

    protected function calculateTotals(): array
    {
        $cart = session('cart', []);
        $menus = Menu::whereIn('id_menu', array_keys($cart))->get()->keyBy('id_menu');

        $items = collect($cart)->map(function ($qty, $menuId) use ($menus) {
            $menu = $menus->get($menuId);
            if (!$menu)
                return null;
            return ['menu' => $menu, 'qty' => $qty];
        })->filter()->values();

        $subtotal = $items->sum(fn($i) => $i['qty'] * (float) $i['menu']->harga);
        $tax = round($subtotal * 0.10);

        // Diskon member sekarang mengikuti promosi (Benefit) yang dibuat Admin,
        // bukan angka tetap. Ambil benefit tipe "diskon" tertinggi yang sudah
        // tercapai poinnya oleh member yang sedang login.
        $diskon = 0;
        if (Auth::check() && Auth::user()->isMember()) {
            $benefit = Benefit::where('aktif', true)
                ->where('tipe', 'diskon')
                ->where('poin_dibutuhkan', '<=', Auth::user()->points)
                ->orderByDesc('poin_dibutuhkan')
                ->first();

            if ($benefit) {
                $diskon = round($subtotal * ((float) $benefit->nilai_diskon / 100));
            }
        }

        $total = $subtotal + $tax - $diskon;

        return [$items, $subtotal, $tax, $diskon, $total];
    }

    protected function ensureCartNotEmpty(): void
    {
        if (empty(session('cart', []))) {
            abort(redirect()->route('menu.index')->with('error', 'Keranjang kamu masih kosong.'));
        }
    }
}
