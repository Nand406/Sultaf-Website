<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailTransaksi extends Model
{
    use HasFactory;

    protected $table = 'detail_transaksis';
    // Karena di migration kita tidak mendefinisikan primary key khusus, kita pakai 'id' standar
    protected $primaryKey = 'id'; 
    public $timestamps = true;

    protected $fillable = [
        'id_transaksi', 
        'id_menu', 
        'jumlah', 
        'subtotal', 
        'harga_satuan'
    ];

    // Relasi balik ke Transaksi Penjualan
    public function transaksi_penjualan()
    {
        return $this->belongsTo(TransaksiPenjualan::class, 'id_transaksi', 'id_transaksi');
    }

    // Relasi ke Menu
    public function menu()
    {
        return $this->belongsTo(Menu::class, 'id_menu', 'id_menu');
    }
}