<?php

namespace App\Http\Controllers;

use App\Models\Benefit;
use App\Models\Menu;
use App\Models\TransaksiItem;
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
            'nomor_meja'   => ['required_if:tipe_pesanan,dine_in', 'nullable', 'integer', 'min:1', 'max:20'],
            'catatan'      => ['nullable', 'string', 'max:500'],
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
            'bukti_pembayaran'  => ['nullable', 'image', 'max:5120'],
            'nama_pelanggan'    => ['required', 'string', 'max:255'],
            'no_telepon'        => ['required', 'string', 'max:20'],
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
            'kode_transaksi'    => 'SLT-' . strtoupper(Str::random(6)),
            'user_id'           => Auth::id(),
            'nama_pelanggan'    => $payment['nama_pelanggan'] ?? null,
            'no_telepon'        => $payment['no_telepon'] ?? null,
            'tipe_pesanan'      => $details['tipe_pesanan'] ?? 'takeaway',
            'nomor_meja'        => $details['nomor_meja'] ?? null,
            'subtotal'          => $subtotal,
            'pajak'             => $tax,
            'service_charge'    => 0,
            'diskon_member'     => $diskon,
            'total_harga'       => $total,
            'status_pesanan'    => 'pending',
            'status_pembayaran' => 'menunggu',
            'metode_pembayaran' => $payment['metode_pembayaran'] ?? null,
            'bukti_pembayaran'  => $payment['bukti_pembayaran'] ?? null,
            'catatan'           => $details['catatan'] ?? null,
            'tgl_transaksi'     => now(),
        ]);

        foreach ($items as $item) {
            TransaksiItem::create([
                'transaksi_penjualan_id' => $transaksi->id,
                'menu_id'                => $item['menu']->id,
                'qty'                    => $item['qty'],
                'harga_satuan'           => $item['menu']->harga_makanan,
            ]);
        }

        session()->forget(['cart', 'checkout.details', 'checkout.payment']);
        return redirect()->route('checkout.finish', $transaksi);
    }

    public function finish(TransaksiPenjualan $transaksi): View
    {
        $transaksi->load('items.menu');
        return view('checkout.finish', compact('transaksi'));
    }

    /* ===================== Helper ===================== */

    protected function calculateTotals(): array
    {
        $cart  = session('cart', []);
        $menus = Menu::whereIn('id', array_keys($cart))->get()->keyBy('id');

        $items = collect($cart)->map(function ($qty, $menuId) use ($menus) {
            $menu = $menus->get($menuId);
            if (! $menu) return null;
            return ['menu' => $menu, 'qty' => $qty];
        })->filter()->values();

        $subtotal = $items->sum(fn ($i) => $i['qty'] * (float) $i['menu']->harga_makanan);
        $tax      = round($subtotal * 0.10);

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
