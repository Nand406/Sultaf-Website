<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $table = 'members';
    protected $primaryKey = 'id_member';
    public $timestamps = true;

    protected $fillable = [
        'id_user', 
        'total_poin', 
        'tanggal_daftar'
    ];

    // Relasi balik ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    // Relasi ke Notifikasi
    public function notifikasis()
    {
        return $this->hasMany(Notifikasi::class, 'id_member', 'id_member');
    }
}