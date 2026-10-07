<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiPenjualan extends Model
{
    use HasFactory;

    // Sesuaikan dengan nama tabel di migration Anda
    protected $table = 'transaksi_penjualans'; 
    
    // Primary key custom
    protected $primaryKey = 'id_transaksi';
    
    // Aktifkan timestamps (karena migration pakai $table->timestamps())
    public $timestamps = true; 

    protected $fillable = [
    'kode_transaksi', 'id_user', 'id_promo', 'tipe_pesanan', 
    'status_pesanan', 'metode_pembayaran', 'diverifikasi_oleh', 
    'total_bayar', 'uang_bayar', 'status_pembayaran', 
    'diskon', 'total_harga', 'tgl_transaksi', 'catatan'
];

    public function user()
    {
        // Pastikan file User.php ada di app/Models
        return $this->belongsTo(User::class, 'id_user', 'id_user'); 
    }

    public function promo()
    {
        return $this->belongsTo(Promo::class, 'id_promo', 'id_promo'); 
    }

    public function detail_transaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'id_transaksi', 'id_transaksi');
    }
}