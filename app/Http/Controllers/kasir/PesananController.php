<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\TransaksiPenjualan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PesananController extends Controller
{
    public function index(): View
    {
        // Kasir bisa MELIHAT seluruh alur (pending & cooking termasuk),
        // tapi hanya boleh MENGUBAH status ready->diantar dan diantar->selesai.
        $pesanan = TransaksiPenjualan::with(['user', 'items.menu'])
            ->where('status_pembayaran', 'terverifikasi')
            ->whereIn('status_pesanan', ['pending', 'cooking', 'ready', 'diantar'])
            ->oldest('tgl_transaksi')
            ->get()
            ->groupBy('status_pesanan');

        $selesaiHariIni = TransaksiPenjualan::where('status_pesanan', 'selesai')
            ->whereDate('updated_at', now())
            ->count();

        return view('kasir.pesanan.index', compact('pesanan', 'selesaiHariIni'));
    }

    // Kasir HANYA boleh mengubah ke 'diantar' atau 'selesai'
    public function updateStatus(Request $request, TransaksiPenjualan $transaksi): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'in:diantar,selesai'],
        ]);

        $validTransitions = [
            'ready'   => 'diantar',
            'diantar' => 'selesai',
        ];

        if (($validTransitions[$transaksi->status_pesanan] ?? null) !== $request->status) {
            return back()->with('error', 'Perubahan status tidak valid untuk pesanan ini.');
        }

        $transaksi->updateStatusPesanan($request->status);

        $pesan = $request->status === 'diantar'
            ? "Pesanan #{$transaksi->kode_transaksi} sedang diantar."
            : "Pesanan #{$transaksi->kode_transaksi} telah selesai.";

        return back()->with('success', $pesan);
    }
}
