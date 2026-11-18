<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class LocaleController extends Controller
{
    // Dipanggil saat tombol ID/EN diklik. Menyimpan pilihan bahasa di session
    // lalu kembali ke halaman semula (URL sebelumnya) dengan bahasa baru aktif.
    public function switch(string $locale): RedirectResponse
    {
        if (! in_array($locale, ['id', 'en'])) {
            $locale = 'id';
        }

        session(['locale' => $locale]);

        return back();
    }
}
