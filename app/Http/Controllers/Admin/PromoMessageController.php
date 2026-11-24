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
        $notifikasis = Notifikasi::with(['user', 'member'])
            ->latest('id_notifikasi')
            ->paginate(15);

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
            'judul'     => ['required', 'string', 'max:255'],
            'isi_pesan' => ['required', 'string'],
            'target'    => ['required', 'in:all,specific'],
            'id_user'   => ['required_if:target,specific', 'nullable', 'exists:users,id_user'],
        ], [
            'id_user.required_if' => 'Silakan pilih user terlebih dahulu jika memilih target User Tertentu.',
        ]);

        if ($validated['target'] === 'all') {
            $users = User::whereIn('role', ['customer', 'member'])->get();

            if ($users->isEmpty()) {
                return back()->withInput()->with('error', 'Tidak ada akun dengan role customer atau member.');
            }

            foreach ($users as $user) {
                Notifikasi::create([
                    'id_user'   => $user->id_user,
                    'id_member' => $user->member->id_member ?? null,
                    'isi_pesan' => $validated['isi_pesan'],
                    'judul'     => $validated['judul'],
                ]);
            }

            return redirect()->route('admin.promo.index')
                ->with('success', "Notifikasi berhasil dikirim ke {$users->count()} user (Semua Member & Customer).");
        }

        $user = User::findOrFail($validated['id_user']);
        Notifikasi::create([
            'id_user'   => $user->id_user,
            'id_member' => $user->member->id_member ?? null,
            'isi_pesan' => $validated['isi_pesan'],
            'judul'     => $validated['judul'],
        ]);

        return redirect()->route('admin.promo.index')
            ->with('success', "Notifikasi berhasil dikirim ke {$user->username}.");
    }

    // DIUBAH: Tangkap $id langsung agar tidak bentrok dengan parameter route {promo}
    public function destroy($id): RedirectResponse
    {
        $notifikasi = Notifikasi::findOrFail($id);
        $notifikasi->delete();

        return back()->with('success', 'Notifikasi berhasil dihapus.');
    }
}