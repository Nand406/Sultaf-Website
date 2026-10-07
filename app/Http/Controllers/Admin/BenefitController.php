<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BenefitController extends Controller
{
    public function index(): View
    {
        // Promo tidak memiliki relasi ke menu di ERD baru
        $benefits = Promo::latest()->get();
        return view('admin.benefit.index', compact('benefits'));
    }

    public function create(): View
    {
        return view('admin.benefit.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatePromo($request);

        Promo::create($validated);

        return redirect()->route('admin.benefit.index')
            ->with('success', 'Promosi baru berhasil disimpan.');
    }

    public function edit(Promo $benefit): View
    {
        return view('admin.benefit.edit', compact('benefit'));
    }

    public function update(Request $request, Promo $benefit): RedirectResponse
    {
        $validated = $this->validatePromo($request);

        $benefit->update($validated);

        return redirect()->route('admin.benefit.index')
            ->with('success', 'Promosi "' . $benefit->nama_promo . '" berhasil diperbarui.');
    }

    public function destroy(Promo $benefit): RedirectResponse
    {
        $nama = $benefit->nama_promo;
        $benefit->delete();

        return back()->with('success', 'Promosi "' . $nama . '" berhasil dihapus.');
    }

    protected function validatePromo(Request $request): array
    {
        // Hanya kolom yang ada di tabel 'promos' yang divalidasi
        return $request->validate([
            'nama_promo'      => ['required', 'string', 'max:255'],
            'potongan_harga'  => ['required', 'numeric', 'min:0'],
            'minimal_poin'    => ['required', 'integer', 'min:0'],
        ]);
    }
}