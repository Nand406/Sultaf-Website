<?php

namespace App\Http\Controllers;

use App\Models\Benefit;
use App\Models\TransaksiPenjualan;
use App\Services\MemberPointService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RewardsController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        $benefits = Benefit::where('aktif', true)->orderBy('poin_dibutuhkan')->get();

        $pointsHistory = TransaksiPenjualan::where('user_id', $user->id)
            ->where('status_pembayaran', 'terverifikasi')
            ->latest('tgl_transaksi')
            ->limit(10)
            ->get()
            ->map(fn ($t) => [
                'transaksi' => $t,
                'poin' => MemberPointService::calculatePoints((float) $t->total_harga),
            ]);

        // Benefit diskon tertinggi yang SUDAH bisa dipakai — ini yang otomatis
        // diterapkan sistem saat member checkout (lihat CheckoutController).
        $activeDiscountBenefit = $benefits
            ->where('tipe', 'diskon')
            ->where('poin_dibutuhkan', '<=', $user->points)
            ->sortByDesc('poin_dibutuhkan')
            ->first();

        // Benefit berikutnya yang belum tercapai, untuk progress bar "X poin lagi"
        $nextBenefit = $benefits
            ->where('poin_dibutuhkan', '>', $user->points)
            ->sortBy('poin_dibutuhkan')
            ->first();

        return view('rewards.index', compact(
            'user', 'benefits', 'pointsHistory', 'activeDiscountBenefit', 'nextBenefit'
        ));
    }
}
