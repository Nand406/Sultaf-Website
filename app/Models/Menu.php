<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model // <--- Kembalikan ke Model
{
    use HasFactory;

    protected $table = 'menus';
    protected $primaryKey = 'id_menu';
    public $timestamps = true;

    protected $fillable = [
        'nama_menu', 
        'harga', 
        'foto_menu', 
        'status_ketersediaan'
    ];

    public function detail_transaksis()
    {
        return $this->hasMany(DetailTransaksi::class, 'id_menu', 'id_menu');
    }
}