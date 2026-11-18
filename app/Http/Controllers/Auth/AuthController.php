<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Member; // Tambahkan ini
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.index', ['tab' => 'login']);
    }

    public function showRegister(): View
    {
        return view('auth.index', ['tab' => 'register']);
    }

    public function login(Request $request): RedirectResponse
    {
        // 1. Validasi menggunakan 'username'
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required'],
        ]);

        // 2. Coba login
        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['username' => __('Username atau password salah.')])
                ->onlyInput('username');
        }

        // 3. Regenerasi session untuk keamanan
        $request->session()->regenerate();

        // 4. Ambil role user dan arahkan ke dashboard yang sesuai
        $role = Auth::user()->role;
        return redirect()->intended($this->redirectBasedOnRole($role));
    }

    public function register(Request $request): RedirectResponse
    {
        // Sesuaikan validasi dengan kolom database baru (username & no_hp)
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'], // 'name' ini akan kita simpan sebagai username
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'phone'    => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Buat User baru
        $user = User::create([
            'username' => $validated['username'], // Gunakan username dari input
            'no_hp'    => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'role'     => 'customer', // Default role untuk pendaftar baru
        ]);

        // Buat data Member untuk user ini (karena dia mendaftar sebagai customer)
        Member::create([
            'id_user' => $user->id_user,
            'total_poin' => 0,
            'tanggal_daftar' => now(),
        ]);

        Auth::login($user);

        return redirect()->route('menu.index');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('menu.index');
    }

    protected function redirectBasedOnRole(string $role): string
    {
        return match ($role) {
            'admin'  => route('admin.dashboard'),
            'owner'  => route('owner.dashboard'),
            'kasir'  => route('kasir.dashboard'),
            'dapur'  => route('dapur.dashboard'),
            default  => route('menu.index'),
        };
    }
}