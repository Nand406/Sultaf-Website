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
        // Ambil pesanan yang sudah diverifikasi tapi belum selesai
        $pesanan = TransaksiPenjualan::with(['user', 'detail_transaksi.menu'])
            ->where('status_pembayaran', 'terverifikasi')
            ->whereIn('status_pesanan', ['pending', 'cooking', 'ready', 'diantar'])
            ->oldest()
            ->get()
            ->groupBy('status_pesanan');

        // Hitung pesanan yang selesai hari ini
        $selesaiHariIni = TransaksiPenjualan::where('status_pesanan', 'selesai')
            ->whereDate('updated_at', now())
            ->count();

        return view('kasir.pesanan.index', compact('pesanan', 'selesaiHariIni'));
    }

    public function updateStatus(Request $request, TransaksiPenjualan $transaksi): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'in:pending,cooking,ready,diantar,selesai'],
        ]);

        // Transisi status yang valid
        $validTransitions = [
            'pending' => ['cooking'],
            'cooking' => ['ready'],
            'ready'   => ['diantar', 'selesai'], // Bisa langsung selesai jika takeaway
            'diantar' => ['selesai'],
        ];

        // Cek apakah status baru diizinkan dari status saat ini
        $allowed = $validTransitions[$transaksi->status_pesanan] ?? [];
        if (!in_array($request->status, $allowed)) {
            return back()->with('error', 'Perubahan status tidak valid untuk pesanan ini.');
        }

        // Update status pesanan
        $transaksi->update(['status_pesanan' => $request->status]);

        $kodeTransaksi = '#SLT-' . str_pad($transaksi->id_transaksi, 5, '0', STR_PAD_LEFT);
        
        $pesan = match($request->status) {
            'cooking' => "Pesanan {$kodeTransaksi} mulai dimasak.",
            'ready'   => "Pesanan {$kodeTransaksi} selesai dimasak, siap diantar.",
            'diantar' => "Pesanan {$kodeTransaksi} sedang diantar ke meja.",
            'selesai' => "Pesanan {$kodeTransaksi} telah diselesaikan.",
            default   => "Status pesanan {$kodeTransaksi} diperbarui.",
        };

        return back()->with('success', $pesan);
    }
}