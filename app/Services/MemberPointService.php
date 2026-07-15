<?php

namespace App\Services;

use App\Models\TransaksiPenjualan;
use App\Models\User;

class MemberPointService
{
    // 1 poin didapat setiap kelipatan Rp 10.000 dari total transaksi
    protected const RUPIAH_PER_POIN = 10000;

    public function awardFromTransaksi(TransaksiPenjualan $transaksi, ?string $noTelepon): ?User
    {
        if (! $noTelepon) {
            return null;
        }

        $normalized = $this->normalizePhone($noTelepon);

        $member = User::where('role', 'member')
            ->get()
            ->first(fn ($u) => $this->normalizePhone((string) $u->phone) === $normalized);

        if (! $member) {
            return null;
        }

        $poinDidapat = self::calculatePoints((float) $transaksi->total_harga);

        if ($poinDidapat > 0) {
            $member->increment('points', $poinDidapat);
        }

        if (! $transaksi->user_id) {
            $transaksi->update(['user_id' => $member->id]);
        }

        return $member;
    }

    // Dipakai di RewardsController untuk menampilkan riwayat poin per transaksi,
    // dan di tempat lain yang butuh hitung poin dari nominal Rupiah.
    public static function calculatePoints(float $amount): int
    {
        return intdiv((int) $amount, self::RUPIAH_PER_POIN);
    }

    protected function normalizePhone(string $phone): string
    {
        $digits = preg_replace('/\D/', '', $phone);

        if (str_starts_with($digits, '62')) {
            $digits = '0' . substr($digits, 2);
        }

        return $digits;
    }
}
