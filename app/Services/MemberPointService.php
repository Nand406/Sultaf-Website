<?php

namespace App\Services;

use App\Models\Member;
use App\Models\TransaksiPenjualan;
use App\Models\User;

class MemberPointService
{
    protected const RUPIAH_PER_POIN = 1000;

    // Parameter kedua (noTelepon) dihapus karena kita tidak membutuhkannya lagi
    public function awardFromTransaksi(TransaksiPenjualan $transaksi): ?User
    {
        // Jika transaksi tidak punya id_user (misal walk-in tanpa no HP), batalkan.
        if (! $transaksi->id_user) {
            return null;
        }

        // Cari user berdasarkan id_user
        $user = User::find($transaksi->id_user);

        if (! $user) {
            return null;
        }

        // Cari data member. Jika belum ada, buatkan otomatis.
        $member = $user->member;
        if (!$member) {
            $member = Member::create([
                'id_user' => $user->id_user,
                'total_poin' => 0,
                'tanggal_daftar' => now(),
            ]);
        }

        // Hitung poin (total_bayar / 1000)
        $poinDidapat = self::calculatePoints((float) $transaksi->total_bayar);

        if ($poinDidapat > 0) {
            $member->increment('total_poin', $poinDidapat);
        }

        return $user;
    }

    public static function calculatePoints(float $amount): int
    {
        return intdiv((int) $amount, self::RUPIAH_PER_POIN);
    }
}