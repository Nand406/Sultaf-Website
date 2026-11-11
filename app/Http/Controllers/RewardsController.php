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

        // 1. Ambil semua transaksi terverifikasi milik user
        $verifiedTransactions = TransaksiPenjualan::where('id_user', $user->id_user)
            ->whereIn('status_pembayaran', ['terverifikasi', 'lunas'])
            ->latest()
            ->get();

        // 2. Hitung Total Poin
        $totalPoin = $verifiedTransactions->sum(function ($trx) {
            return floor($trx->total_bayar / 1000);
        });

        // 3. Cari ID Promo yang sudah pernah dipakai user ini
        $usedPromoIds = $verifiedTransactions->whereNotNull('id_promo')
            ->pluck('id_promo')
            ->unique()
            ->toArray();

        // 4. Ambil daftar Promo yang BELUM dipakai
        $benefits = Promo::whereNotIn('id_promo', $usedPromoIds)
            ->orderBy('minimal_poin')
            ->get();

        // 5. Riwayat Poin (Ambil 10 terakhir)
        $pointsHistory = $verifiedTransactions->take(10)->map(function ($trx) {
            return [
                'transaksi' => $trx,
                'poin'      => floor($trx->total_bayar / 1000), 
            ];
        });

        // 6. Logika Promo & Benefit
        $claimablePromos = $benefits->filter(fn ($p) => $totalPoin >= $p->minimal_poin);
        $nextBenefit = $benefits->first(fn ($p) => $totalPoin < $p->minimal_poin);
        
        // Active discount adalah promo tertinggi yang tersedia (belum dipakai) dan poinnya cukup
        $activeDiscountBenefit = $claimablePromos->sortByDesc('minimal_poin')->first();

        return view('rewards.index', compact(
            'user', 'member', 'totalPoin',
            'benefits', 'claimablePromos', 'nextBenefit',
            'activeDiscountBenefit', 'pointsHistory'
        ));
    }
}