<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiItem extends Model
{
    protected $fillable = [
        'transaksi_penjualan_id', 'menu_id', 'qty', 'harga_satuan', 'catatan',
    ];

    public function transaksi()
    {
        return $this->belongsTo(TransaksiPenjualan::class, 'transaksi_penjualan_id');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function getSubtotalAttribute(): float
    {
        return $this->qty * (float) $this->harga_satuan;
    }
}
