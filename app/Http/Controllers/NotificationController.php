<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        // Ambil notifikasi untuk user ini
        $notifikasis = Notifikasi::where('id_user', $user->id_user)
            ->latest()
            ->paginate(15);

        return view('notifications.index', compact('notifikasis'));
    }
}