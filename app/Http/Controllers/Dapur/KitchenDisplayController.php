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
        $pesanan = TransaksiPenjualan::with(['user', 'detail_transaksi.menu'])
    ->where('status_pembayaran', 'terverifikasi')
    ->whereIn('status_pesanan', ['pending', 'cooking', 'ready']) // <--- Tambahkan 'ready'
    ->oldest()
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
            'status' => ['required', 'in:pending,cooking,ready,selesai'],
        ]);

        // Transisi status yang valid
        $validTransitions = [
            'pending' => 'cooking',
            'cooking' => 'ready',
            'ready'   => 'selesai',
        ];

        if (($validTransitions[$transaksi->status_pesanan] ?? null) !== $request->status) {
            return back()->with('error', 'Perubahan status tidak valid untuk pesanan ini.');
        }

        // DIUBAH: Langsung update, tidak pakai method yang tidak ada
        $transaksi->update(['status_pesanan' => $request->status]);

        // DIUBAH: Format kode transaksi pakai id_transaksi
        $kodeTransaksi = '#SLT-' . str_pad($transaksi->id_transaksi, 5, '0', STR_PAD_LEFT);
        
        $pesan = $request->status === 'cooking'
            ? "Pesanan {$kodeTransaksi} mulai dimasak."
            : "Pesanan {$kodeTransaksi} selesai dimasak, siap diantar kasir.";

        return back()->with('success', $pesan);
    }
}
