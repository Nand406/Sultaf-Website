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
        $menunggu = TransaksiPenjualan::with(['user', 'detail_transaksi.menu'])
            ->where('status_pembayaran', 'menunggu')
            ->oldest()
            ->get();

        $terverifikasiHariIni = TransaksiPenjualan::where('status_pembayaran', 'terverifikasi')
            ->whereDate('updated_at', now())
            ->count();

        return view('kasir.pembayaran.index', compact('menunggu', 'terverifikasiHariIni'));
    }

    public function verify(TransaksiPenjualan $transaksi): RedirectResponse
    {
        $transaksi->update([
            'status_pembayaran' => 'terverifikasi',
            'status_pesanan'    => 'pending',
        ]);

        // DIUBAH: Menggunakan no_telepon (pastikan kolom ini ada di database)
        $member = $this->memberPoints->awardFromTransaksi($transaksi, $transaksi->no_telepon);

        // DIUBAH: Menggunakan format ID baru, bukan kode_transaksi
        $kodeTransaksi = '#SLT-' . str_pad($transaksi->id_transaksi, 5, '0', STR_PAD_LEFT);
        $pesan = "Pembayaran {$kodeTransaksi} berhasil diverifikasi & dikirim ke dapur.";
        
        if ($member) {
            // DIUBAH: Menggunakan username dari relasi user
            $pesan .= " Poin member atas nama {$member->user->username} bertambah.";
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

        // DIUBAH: Menggunakan format ID baru, bukan kode_transaksi
        $kodeTransaksi = '#SLT-' . str_pad($transaksi->id_transaksi, 5, '0', STR_PAD_LEFT);
        return back()->with('success', "Pembayaran {$kodeTransaksi} ditolak.");
    }
}