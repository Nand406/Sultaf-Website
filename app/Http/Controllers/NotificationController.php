<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        // 1. Ambil ID notifikasi tertinggi milik user ini
        $lastNotifId = Notifikasi::where('id_user', $user->id_user)->max('id_notifikasi');
        
        // 2. Simpan ID tersebut ke Cache (berlaku 30 hari)
        // Jika tidak ada notifikasi, simpan 0
        Cache::put('notif_last_id_' . $user->id_user, $lastNotifId ?? 0, now()->addDays(30));

        // 3. Ambil daftar notifikasi
        $notifikasis = Notifikasi::where('id_user', $user->id_user)
            ->latest()
            ->paginate(15);

        return view('notifications.index', compact('notifikasis'));
    }
}