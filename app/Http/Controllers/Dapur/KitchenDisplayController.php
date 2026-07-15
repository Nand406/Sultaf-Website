<?php

namespace App\Http\Controllers\Dapur;

use App\Http\Controllers\Controller;
use App\Models\TransaksiPenjualan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KitchenDisplayController extends Controller
{
    // Papan dapur: hanya menampilkan pesanan yang perlu dimasak (pending & cooking)
    public function index(): View
    {
        $pesanan = TransaksiPenjualan::with(['user', 'items.menu'])
            ->where('status_pembayaran', 'terverifikasi')
            ->whereIn('status_pesanan', ['pending', 'cooking'])
            ->oldest('tgl_transaksi')
            ->get()
            ->groupBy('status_pesanan');

        $selesaiMasakHariIni = TransaksiPenjualan::where('status_pesanan', 'ready')
            ->whereDate('updated_at', now())
            ->count();

        return view('dapur.pesanan.index', compact('pesanan', 'selesaiMasakHariIni'));
    }

    // Dapur HANYA boleh mengubah ke status 'cooking' (mulai masak) atau 'ready' (selesai masak)
    public function updateStatus(Request $request, TransaksiPenjualan $transaksi): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'in:cooking,ready'],
        ]);

        // Pastikan urutan transisi benar: pending->cooking atau cooking->ready saja
        $validTransitions = [
            'pending' => 'cooking',
            'cooking' => 'ready',
        ];

        if (($validTransitions[$transaksi->status_pesanan] ?? null) !== $request->status) {
            return back()->with('error', 'Perubahan status tidak valid untuk pesanan ini.');
        }

        $transaksi->updateStatusPesanan($request->status);

        $pesan = $request->status === 'cooking'
            ? "Pesanan #{$transaksi->kode_transaksi} mulai dimasak."
            : "Pesanan #{$transaksi->kode_transaksi} selesai dimasak, siap diantar kasir.";

        return back()->with('success', $pesan);
    }
}
