<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    use HasFactory;

    protected $table = 'promos';
    protected $primaryKey = 'id_promo';
    public $timestamps = true;

    protected $fillable = [
        'nama_promo', 
        'potongan_harga', 
        'minimal_poin'
    ];

    // Relasi ke Transaksi Penjualan
    public function transaksi_penjualans()
    {
        return $this->hasMany(TransaksiPenjualan::class, 'id_promo', 'id_promo');
    }
}