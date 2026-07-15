<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'phone', 'avatar', 'points',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'points' => 'integer',
        ];
    }

    public function transaksi()
    {
        return $this->hasMany(TransaksiPenjualan::class);
    }

    public function isAdmin(): bool { return $this->role === 'admin'; }
    public function isOwner(): bool { return $this->role === 'owner'; }
    public function isKasir(): bool { return $this->role === 'kasir'; }
    public function isDapur(): bool { return $this->role === 'dapur'; }
    public function isMember(): bool { return $this->role === 'member'; }
    public function isPelanggan(): bool { return $this->role === 'pelanggan'; }

    public function isStaff(): bool
    {
        return in_array($this->role, ['admin', 'owner', 'kasir', 'dapur']);
    }
}
