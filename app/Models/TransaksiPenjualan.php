<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiPenjualan extends Model
{
    use HasFactory;

    protected $table = 'transaksi_penjualans';
    protected $primaryKey = 'id_transaksi';
    public $timestamps = true;

    protected $fillable = [
        'id_user', 
        'id_promo', 
        'tipe_pesanan', 
        'status_pesanan',
        'metode_pembayaran', 
        'diverifikasi_oleh', 
        'no_meja', // <--- PASTIKAN INI ADA
        'status_pembayaran', 
        'total_bayar', 
        'uang_bayar', 
        'diskon', 
        'total_harga',
        'no_telepon',
        'snap_token',
    ];

    // ... (relasi user, detail_transaksi, promo tetap sama)
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function detail_transaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'id_transaksi', 'id_transaksi');
    }

    public function promo()
    {
        return $this->belongsTo(Promo::class, 'id_promo', 'id_promo');
    }
}