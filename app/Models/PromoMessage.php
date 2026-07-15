<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoMessage extends Model
{
    protected $fillable = [
        'judul', 'pesan', 'target_role', 'dikirim_oleh',
    ];

    public function pengirim()
    {
        return $this->belongsTo(User::class, 'dikirim_oleh');
    }

    // Ambil pesan yang relevan untuk user tertentu.
    // Karena hanya ada 1 tipe member, semua member dapat pesan yang sama.
    public static function untukUser(User $user)
    {
        return static::query()
            ->where(function ($q) use ($user) {
                $q->where('target_role', 'semua');

                if ($user->isMember()) {
                    $q->orWhere('target_role', 'member');
                }
            })
            ->latest()
            ->get();
    }
}
