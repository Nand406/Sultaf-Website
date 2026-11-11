<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use App\Models\Menu;
use App\Models\DetailTransaksi;
use App\Models\TransaksiPenjualan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Midtrans\Config;
use Midtrans\Snap;

class CheckoutController extends Controller
{
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

    public function payment(): View
    {
        $this->ensureCartNotEmpty();
        [, $subtotal, $tax, $diskon, $total] = $this->calculateTotals();
        $pointsEarned = floor($total / 1000); 
        return view('checkout.payment', compact('subtotal', 'tax', 'diskon', 'total', 'pointsEarned'));
    }

    public function storePayment(Request $request): JsonResponse
    {
        $this->ensureCartNotEmpty();

        $validated = $request->validate([
            'metode_pembayaran' => ['required', 'in:qris,cash'],
            'nama_pelanggan' => ['required', 'string', 'max:255'],
            'no_telepon' => ['required', 'string', 'max:20'],
        ]);

        session(['checkout.payment' => $validated]);
        
        try {
            $transaksi = $this->createTransaksi();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

        // 2. Jika metode pembayaran QRIS, generate token Midtrans
        if ($validated['metode_pembayaran'] === 'qris') {
            Config::$serverKey = config('midtrans.server_key');
            Config::$isProduction = filter_var(config('midtrans.is_production'), FILTER_VALIDATE_BOOLEAN);
            Config::$isSanitized = filter_var(config('midtrans.is_sanitized'), FILTER_VALIDATE_BOOLEAN);
            Config::$is3ds = filter_var(config('midtrans.is_3ds'), FILTER_VALIDATE_BOOLEAN);

            Config::$curlOptions = [
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_HTTPHEADER => [],
            ];

            // ================= TAMBAHAN: SUSUN RINCIAN PRODUK UNTUK MIDTRANS =================
            $transaksi->load('detail_transaksi.menu');
            $itemDetails = [];

            // 1. Masukkan item menu
            foreach ($transaksi->detail_transaksi as $detail) {
                $itemDetails[] = [
                    'id'       => (string) $detail->id_menu,
                    'price'    => (int) $detail->harga_satuan,
                    'quantity' => (int) $detail->jumlah,
                    'name'     => $detail->menu->nama_menu,
                ];
            }

            // 2. Hitung Pajak & Masukkan ke rincian
            $subtotal = $transaksi->detail_transaksi->sum('subtotal');
            $tax = $transaksi->total_bayar - $subtotal + $transaksi->diskon;
            
            if ($tax > 0) {
                $itemDetails[] = [
                    'id'       => 'TAX',
                    'price'    => (int) $tax,
                    'quantity' => 1,
                    'name'     => 'Pajak (10%)',
                ];
            }

            // 3. Masukkan Diskon (jika ada)
            if ($transaksi->diskon > 0) {
                $itemDetails[] = [
                    'id'       => 'DISCOUNT',
                    'price'    => -(int) $transaksi->diskon,
                    'quantity' => 1,
                    'name'     => 'Diskon Member',
                ];
            }
            // ================================================================================

            $params = [
                'transaction_details' => [
                    'order_id' => 'SLT-' . $transaksi->id_transaksi . '-' . time(),
                    'gross_amount' => (int) $transaksi->total_bayar,
                ],
                'customer_details' => [
                    'first_name' => $validated['nama_pelanggan'],
                    'phone' => $validated['no_telepon'],
                ],
                'item_details' => $itemDetails, // <-- PARAMETER BARU
            ];

            try {
                $snapToken = Snap::getSnapToken($params);
                $transaksi->update(['snap_token' => $snapToken]);

                return response()->json([
                    'snap_token' => $snapToken,
                    'redirect_url' => route('checkout.finish', $transaksi->id_transaksi)
                ]);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }

        // 3. Jika Cash, langsung kembalikan redirect URL (tanpa Midtrans)
        return response()->json([
            'redirect_url' => route('checkout.finish', $transaksi->id_transaksi)
        ]);
    }

    protected function createTransaksi(): TransaksiPenjualan
    {
        [$items, $subtotal, $tax, $diskon, $total] = $this->calculateTotals();
        
        if ($items->isEmpty() || $total <= 0) {
            throw new \Exception('Keranjang kosong atau total tidak valid.');
        }

        $details = session('checkout.details', []);
        $payment = session('checkout.payment', []);

        $transaksi = TransaksiPenjualan::create([
            'id_user' => Auth::id(),
            'id_promo' => null, 
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

        return $transaksi;
    }

    public function finish(TransaksiPenjualan $transaksi): View
    {
        $transaksi->load('detail_transaksi.menu');
        return view('checkout.finish', compact('transaksi'));
    }

    public function callback(Request $request): JsonResponse
    {
        $serverKey = config('midtrans.server_key');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed == $request->signature_key) {
            $orderIdParts = explode('-', $request->order_id);
            $id_transaksi = $orderIdParts[1] ?? null;

            if ($id_transaksi) {
                $transaksi = TransaksiPenjualan::find($id_transaksi);
                if ($transaksi) {
                    if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
                        $transaksi->update([
                            'status_pembayaran' => 'terverifikasi',
                            'status_pesanan'    => 'cooking' 
                        ]);

                        $memberPoints = app(\App\Services\MemberPointService::class);
                        $memberPoints->awardFromTransaksi($transaksi);

                    } elseif (in_array($request->transaction_status, ['deny', 'expire', 'cancel'])) {
                        $transaksi->update([
                            'status_pembayaran' => 'ditolak',
                            'status_pesanan'    => 'dibatalkan'
                        ]);
                    }
                }
            }
        }
        return response()->json(['message' => 'Callback received']);
    }

    protected function calculateTotals(): array
    {
        $cart = session('cart', []);
        $menus = Menu::whereIn('id_menu', array_keys($cart))->get()->keyBy('id_menu');

        $items = collect($cart)->map(function ($qty, $menuId) use ($menus) {
            $menu = $menus->get($menuId);
            if (!$menu) return null;
            return ['menu' => $menu, 'qty' => $qty];
        })->filter()->values();

        $subtotal = $items->sum(fn($i) => $i['qty'] * (float) $i['menu']->harga);
        $tax = round($subtotal * 0.10);

        $diskon = 0;
        if (Auth::check()) {
            $user = Auth::user();

            $totalPoin = \App\Models\TransaksiPenjualan::where('id_user', $user->id_user)
                ->whereIn('status_pembayaran', ['terverifikasi', 'lunas'])
                ->get()
                ->sum(function ($trx) {
                    return floor($trx->total_bayar / 1000);
                });

            $usedPromoIds = \App\Models\TransaksiPenjualan::where('id_user', $user->id_user)
                ->whereNotNull('id_promo')
                ->pluck('id_promo')
                ->unique()
                ->toArray();

            $promo = Promo::where('minimal_poin', '<=', $totalPoin)
                ->whereNotIn('id_promo', $usedPromoIds)
                ->orderByDesc('minimal_poin')
                ->first();

            if ($promo) {
                $diskon = $promo->potongan_harga;
            }
        }

        $total = max(0, $subtotal + $tax - $diskon);
        return [$items, $subtotal, $tax, $diskon, $total];
    }
    
    protected function ensureCartNotEmpty(): void
    {
        if (empty(session('cart', []))) {
            abort(redirect()->route('menu.index')->with('error', 'Keranjang kamu masih kosong.'));
        }
    }
}