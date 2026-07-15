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
    public function __construct(protected MemberPointService $memberPoints) {}

    public function index(): View
    {
        $menunggu = TransaksiPenjualan::with(['user', 'items.menu'])
            ->where('status_pembayaran', 'menunggu')
            ->oldest('tgl_transaksi')
            ->get();

        $terverifikasiHariIni = TransaksiPenjualan::where('status_pembayaran', 'terverifikasi')
            ->whereDate('updated_at', now())
            ->count();

        return view('kasir.pembayaran.index', compact('menunggu', 'terverifikasiHariIni'));
    }

    // Kasir menyetujui pembayaran -> masuk antrian dapur + poin member ditambahkan
    // otomatis via nomor HP yang dicatat saat checkout (kalau nomornya terdaftar sbg member)
    public function verify(TransaksiPenjualan $transaksi): RedirectResponse
    {
        $transaksi->update([
            'status_pembayaran' => 'terverifikasi',
            'status_pesanan'    => 'pending',
        ]);

        $member = $this->memberPoints->awardFromTransaksi($transaksi, $transaksi->no_telepon);

        $pesan = "Pembayaran #{$transaksi->kode_transaksi} berhasil diverifikasi & dikirim ke dapur.";
        if ($member) {
            $pesan .= " Poin member atas nama {$member->name} bertambah.";
        }

        return back()->with('success', $pesan);
    }

    public function reject(Request $request, TransaksiPenjualan $transaksi): RedirectResponse
    {
        $request->validate([
            'alasan' => ['nullable', 'string', 'max:255'],
        ]);

        $transaksi->update([
            'status_pembayaran' => 'ditolak',
            'status_pesanan'    => 'dibatalkan',
            'catatan'           => trim(($transaksi->catatan ?? '') . ' | Ditolak kasir: ' . ($request->alasan ?? '-')),
        ]);

        return back()->with('success', "Pembayaran #{$transaksi->kode_transaksi} ditolak.");
    }
}
