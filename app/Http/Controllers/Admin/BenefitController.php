<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Benefit;
use App\Models\Menu;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BenefitController extends Controller
{
    public function index(): View
    {
        $benefits = Benefit::with('menu')->latest()->get();
        return view('admin.benefit.index', compact('benefits'));
    }

    public function create(): View
    {
        $menus = Menu::orderBy('nama_makanan')->get();
        return view('admin.benefit.create', compact('menus'));
    }

    // setara kelolaBenefit() pada Diagram Kelas (Admin menentukan minimum poin + nominal potongan)
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateBenefit($request);

        Benefit::create($validated);

        return redirect()->route('admin.benefit.index')->with('success', 'Promosi/benefit baru berhasil disimpan.');
    }

    public function edit(Benefit $benefit): View
    {
        $menus = Menu::orderBy('nama_makanan')->get();
        return view('admin.benefit.edit', compact('benefit', 'menus'));
    }

    public function update(Request $request, Benefit $benefit): RedirectResponse
    {
        $validated = $this->validateBenefit($request);

        $benefit->update($validated);

        return redirect()->route('admin.benefit.index')->with('success', 'Promosi "' . $benefit->nama_benefit . '" berhasil diperbarui.');
    }

    public function destroy(Benefit $benefit): RedirectResponse
    {
        $nama = $benefit->nama_benefit;
        $benefit->delete();

        return back()->with('success', 'Promosi "' . $nama . '" berhasil dihapus.');
    }

    // Aktif/nonaktifkan cepat dari daftar
    public function toggle(Benefit $benefit): RedirectResponse
    {
        $benefit->update(['aktif' => ! $benefit->aktif]);

        return back()->with('success', 'Status promosi "' . $benefit->nama_benefit . '" diperbarui.');
    }

    protected function validateBenefit(Request $request): array
    {
        $validated = $request->validate([
            'nama_benefit'    => ['required', 'string', 'max:255'],
            'deskripsi'       => ['nullable', 'string', 'max:500'],
            'poin_dibutuhkan' => ['required', 'integer', 'min:1'],
            'tipe'            => ['required', 'in:diskon,gratis_menu'],
            'nilai_diskon'    => ['nullable', 'numeric', 'min:0'],
            'menu_id'         => ['nullable', 'exists:menus,id'],
        ]);

        $validated['aktif'] = $request->boolean('aktif', true);

        return $validated;
    }
}
