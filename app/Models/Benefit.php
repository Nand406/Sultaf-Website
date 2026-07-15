<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Benefit extends Model
{
    protected $fillable = [
        'nama_benefit', 'deskripsi', 'poin_dibutuhkan', 'tipe',
        'nilai_diskon', 'menu_id', 'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    // setara getBenefitDetail(): void pada Diagram Kelas
    public function isClaimableBy(User $user): bool
    {
        return $this->aktif && $user->points >= $this->poin_dibutuhkan;
    }
}
