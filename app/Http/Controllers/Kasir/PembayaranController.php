<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\TransaksiPenjualan;
use App\Services\MemberPointService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PembayaranController extends Controller
{
    public function __construct(protected MemberPointService $memberPoints)
    {
    }

    public function index(): View
    {
        $menunggu = TransaksiPenjualan::with(['user', 'detail_transaksi.menu'])
            ->where('status_pembayaran', 'menunggu')
            ->where('metode_pembayaran', 'cash') // <--- TAMBAHKAN INI: Hanya tampilkan Cash
            ->oldest()
            ->get();

        $terverifikasiHariIni = TransaksiPenjualan::where('status_pembayaran', 'terverifikasi')
            ->whereDate('updated_at', now())
            ->count();

        return view('kasir.pembayaran.index', compact('menunggu', 'terverifikasiHariIni'));
    }

    public function verify(Request $request, TransaksiPenjualan $transaksi): RedirectResponse
    {
        $request->validate([
            'uang_bayar' => ['required', 'numeric', 'min:' . $transaksi->total_bayar],
        ]);

        $transaksi->update([
            'status_pembayaran' => 'terverifikasi',
            'status_pesanan'    => 'pending',
            'uang_bayar'        => $request->uang_bayar, 
        ]);

        $member = $this->memberPoints->awardFromTransaksi($transaksi);
        $kodeTransaksi = '#SLT-' . str_pad($transaksi->id_transaksi, 5, '0', STR_PAD_LEFT);
        $pesan = "Pembayaran {$kodeTransaksi} berhasil diverifikasi.";
        if ($member) {
            $pesan .= " Poin member atas nama {$member->username} bertambah.";
        }

        return redirect()->route('kasir.pembayaran.receipt', $transaksi)->with('success', $pesan);
    }

    public function receipt(TransaksiPenjualan $transaksi): View
    {
        $transaksi->load(['detail_transaksi.menu', 'user']);
        return view('kasir.pembayaran.receipt', compact('transaksi'));
    }

    public function reject(Request $request, TransaksiPenjualan $transaksi): RedirectResponse
    {
        $request->validate([
            'alasan' => ['nullable', 'string', 'max:255'],
        ]);

        $transaksi->update([
            'status_pembayaran' => 'ditolak',
            'status_pesanan' => 'dibatalkan',
            'catatan' => trim(($transaksi->catatan ?? '') . ' | Ditolak kasir: ' . ($request->alasan ?? '-')),
        ]);

        $kodeTransaksi = '#SLT-' . str_pad($transaksi->id_transaksi, 5, '0', STR_PAD_LEFT);
        return back()->with('success', "Pembayaran {$kodeTransaksi} ditolak.");
    }
}