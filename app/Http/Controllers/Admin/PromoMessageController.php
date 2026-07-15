<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PromoMessage;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PromoMessageController extends Controller
{
    public function index(): View
    {
        $pesanTerkirim = PromoMessage::with('pengirim')->latest()->paginate(10);

        $jumlahMember = User::where('role', 'member')->count();
        $jumlahSemua  = User::whereIn('role', ['pelanggan', 'member'])->count();

        return view('admin.promo.index', compact('pesanTerkirim', 'jumlahMember', 'jumlahSemua'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'judul'       => ['required', 'string', 'max:255'],
            'pesan'       => ['required', 'string', 'max:1000'],
            'target_role' => ['required', 'in:semua,member'],
        ]);

        $validated['dikirim_oleh'] = Auth::id();

        PromoMessage::create($validated);

        return back()->with('success', 'Pesan promosi berhasil dikirim.');
    }

    public function destroy(PromoMessage $promo): RedirectResponse
    {
        $promo->delete();

        return back()->with('success', 'Pesan promosi berhasil dihapus.');
    }
}
