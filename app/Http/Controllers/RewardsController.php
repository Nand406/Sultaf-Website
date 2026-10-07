<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use App\Models\TransaksiPenjualan;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RewardsController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $member = $user->member ?? null;
        $totalPoin = $member ? $member->total_poin : 0;

        // Ambil semua promo
        $benefits = Promo::orderBy('minimal_poin')->get();

        // Promo yang sudah bisa diklaim
        $claimablePromos = $benefits->filter(fn ($p) => $totalPoin >= $p->minimal_poin);

        // Promo berikutnya (untuk progress bar)
        $nextBenefit = $benefits->first(fn ($p) => $totalPoin < $p->minimal_poin);

        // Promo diskon yang sudah aktif (poin tertinggi yang tercapai)
        $activeDiscountBenefit = $claimablePromos->sortByDesc('minimal_poin')->first();

        // Riwayat poin dari transaksi user
        $pointsHistory = collect();
        if ($member) {
            $pointsHistory = TransaksiPenjualan::where('id_user', $user->id_user)
                ->where('status_pembayaran', 'terverifikasi')
                ->latest()
                ->limit(10)
                ->get()
                ->map(function ($trx) {
                    return [
                        'transaksi' => $trx,
                        'poin'      => floor($trx->total_bayar / 10000), // 1 poin per Rp 10.000
                    ];
                });
        }

        return view('rewards.index', compact(
            'user', 'member', 'totalPoin',
            'benefits', 'claimablePromos', 'nextBenefit',
            'activeDiscountBenefit', 'pointsHistory'
        ));
    }
}