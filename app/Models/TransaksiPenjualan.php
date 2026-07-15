<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiPenjualan extends Model
{
    protected $table = 'transaksi_penjualans';

    protected $fillable = [
        'kode_transaksi', 'user_id', 'nama_pelanggan', 'no_telepon',
        'tipe_pesanan', 'nomor_meja',
        'subtotal', 'pajak', 'service_charge', 'diskon_member', 'total_harga',
        'status_pesanan', 'status_pembayaran', 'metode_pembayaran',
        'bukti_pembayaran', 'catatan', 'tgl_transaksi',
    ];

    protected $casts = [
        'tgl_transaksi' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(TransaksiItem::class, 'transaksi_penjualan_id');
    }

    public function updateStatusPesanan(string $status): bool
    {
        return $this->update(['status_pesanan' => $status]);
    }

    public function updateStatusPembayaran(string $status): bool
    {
        return $this->update(['status_pembayaran' => $status]);
    }

    public static function getLaporanPenjualan($from = null, $to = null)
    {
        return static::query()
            ->when($from, fn ($q) => $q->whereDate('tgl_transaksi', '>=', $from))
            ->when($to, fn ($q) => $q->whereDate('tgl_transaksi', '<=', $to))
            ->where('status_pembayaran', 'terverifikasi')
            ->orderByDesc('tgl_transaksi')
            ->get();
    }
}
