<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id_user';
    public $timestamps = true;

    protected $fillable = [
        'username', 
        'password', 
        'no_hp', 
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relasi ke Member
    public function member()
    {
        return $this->hasOne(Member::class, 'id_user', 'id_user');
    }

    // Relasi ke Transaksi Penjualan
    public function transaksi_penjualans()
    {
        return $this->hasMany(TransaksiPenjualan::class, 'id_user', 'id_user');
    }

    // Relasi ke Notifikasi
    public function notifikasis()
    {
        return $this->hasMany(Notifikasi::class, 'id_user', 'id_user');
    }

    // --- FUNGSI BANTUAN ROLE ---
    public function isOwner()   { return $this->role === 'owner'; }
    public function isAdmin()   { return $this->role === 'admin'; }
    public function isKasir()   { return $this->role === 'kasir'; }
    public function isDapur()   { return $this->role === 'dapur'; }
    public function isCustomer(){ return $this->role === 'customer'; }
    
    public function isMember()
    {
        return $this->member()->exists();
    }
}