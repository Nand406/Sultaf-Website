<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    protected $fillable = ['nama', 'stok', 'satuan', 'ambang_batas'];

    protected $casts = [
        'stok' => 'decimal:2',
        'ambang_batas' => 'decimal:2',
    ];

    public function isLowStock(): bool
    {
        return $this->stok <= $this->ambang_batas;
    }

    public function scopeLowStock($query)
    {
        return $query->whereColumn('stok', '<=', 'ambang_batas');
    }
}
