<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PromoMessageController extends Controller
{
    public function index(): View
    {
        $notifikasis = Notifikasi::with(['user', 'member'])->latest()->paginate(15);
        return view('admin.promo.index', compact('notifikasis'));
    }

    public function create(): View
    {
        $users = User::whereIn('role', ['customer', 'member'])->orderBy('username')->get();
        return view('admin.promo.create', compact('users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'judul'    => ['required', 'string', 'max:255'],
            'id_pesan' => ['required', 'string'],
            'target'   => ['required', 'in:all,specific'],
            'id_user'  => ['nullable', 'exists:users,id_user'],
        ]);

        if ($validated['target'] === 'all') {
            $users = User::whereIn('role', ['customer', 'member'])->get();

            foreach ($users as $user) {
                Notifikasi::create([
                    'id_user'   => $user->id_user,
                    'id_member' => $user->member->id_member ?? null,
                    'id_pesan'  => $validated['id_pesan'],
                    'judul'     => $validated['judul'],
                ]);
            }

            return redirect()->route('admin.promo.index')
                ->with('success', "Notifikasi berhasil dikirim ke {$users->count()} user.");
        }

        $user = User::findOrFail($validated['id_user']);
        Notifikasi::create([
            'id_user'   => $user->id_user,
            'id_member' => $user->member->id_member ?? null,
            'id_pesan'  => $validated['id_pesan'],
            'judul'     => $validated['judul'],
        ]);

        return redirect()->route('admin.promo.index')
            ->with('success', "Notifikasi berhasil dikirim ke {$user->username}.");
    }

    public function destroy(Notifikasi $notifikasi): RedirectResponse
    {
        $notifikasi->delete();
        return back()->with('success', 'Notifikasi berhasil dihapus.');
    }
}