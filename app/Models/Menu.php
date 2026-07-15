<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        'category_id', 'nama_makanan', 'deskripsi', 'foto_makanan',
        'harga_makanan', 'harga_modal', 'rating', 'spice_level', 'is_halal', 'habis',
    ];

    protected $casts = [
        'harga_makanan' => 'decimal:2',
        'harga_modal' => 'decimal:2',
        'is_halal' => 'boolean',
        'habis' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeTersedia($query)
    {
        return $query->where('habis', false);
    }

    public function getHargaFormatAttribute(): string
    {
        return 'Rp ' . number_format((float) $this->harga_makanan, 0, ',', '.');
    }

    // Keuntungan per unit menu (dipakai di grafik keuntungan Owner)
    public function getKeuntunganPerUnitAttribute(): float
    {
        return (float) $this->harga_makanan - (float) $this->harga_modal;
    }
}
