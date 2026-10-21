<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\TransaksiPenjualan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LaporanController extends Controller
{
    public function index(Request $request): View
    {
        $preset = $request->input('preset', '7hari');

        // Tentukan rentang tanggal berdasarkan preset (sesuai View)
        switch ($preset) {
            case 'hari-ini':
                $from = now()->startOfDay();
                $to   = now()->endOfDay();
                break;
            case 'bulan-ini':
                $from = now()->startOfMonth();
                $to   = now()->endOfMonth();
                break;
            case 'tahun-ini':
                $from = now()->startOfYear();
                $to   = now()->endOfYear();
                break;
            case '30hari':
                $from = now()->subDays(30)->startOfDay();
                $to   = now()->endOfDay();
                break;
            case '7hari':
            default:
                $from = now()->subDays(7)->startOfDay();
                $to   = now()->endOfDay();
                break;
        }

        if ($preset === 'custom' && $request->filled('from') && $request->filled('to')) {
            $from = Carbon::parse($request->input('from'))->startOfDay();
            $to   = Carbon::parse($request->input('to'))->endOfDay();
        }

        $baseQuery = fn () => TransaksiPenjualan::where('status_pembayaran', 'terverifikasi')
            ->whereBetween('created_at', [$from, $to]);

        /* ==================== Ringkasan ==================== */
        $transaksi = $baseQuery()->get();
        $totalOmzet = $transaksi->sum('total_bayar');
        $totalTransaksi = $transaksi->count();
        $rataRataTransaksi = $totalTransaksi > 0 ? $totalOmzet / $totalTransaksi : 0;
        $totalKeuntungan = $totalOmzet;
        $totalDiskon = $transaksi->sum('diskon');

        /* ==================== Top Menu ==================== */
        $topMenu = $baseQuery()
            ->with('detail_transaksi.menu')
            ->get()
            ->pluck('detail_transaksi')
            ->flatten()
            ->groupBy('id_menu')
            ->map(function ($group) {
                $subtotal = $group->sum('subtotal');
                return [
                    'menu'       => $group->first()->menu,
                    'qty'        => $group->sum('jumlah'),
                    'revenue'    => $subtotal,
                    'keuntungan' => $subtotal,
                ];
            })
            ->sortByDesc('qty')
            ->take(10)
            ->values();

        /* ==================== Kategori Breakdown ==================== */
        // Tidak ada tabel categories di ERD baru
        $kategoriBreakdown = collect();

        /* ==================== Metode Pembayaran ==================== */
        $metodePembayaran = $baseQuery()
            ->selectRaw('metode_pembayaran, COUNT(*) as jumlah, SUM(total_bayar) as total')
            ->groupBy('metode_pembayaran')
            ->orderByDesc('total')
            ->get()
            ->map(fn ($row) => [
                'metode' => $row->metode_pembayaran,
                'jumlah' => $row->jumlah,
                'total'  => $row->total,
            ]);

        /* ==================== Tipe Pesanan ==================== */
        $tipePesanan = $baseQuery()
            ->selectRaw('tipe_pesanan, COUNT(*) as jumlah, SUM(total_bayar) as total')
            ->groupBy('tipe_pesanan')
            ->orderByDesc('total')
            ->get()
            ->map(fn ($row) => [
                'tipe'   => $row->tipe_pesanan,
                'jumlah' => $row->jumlah,
                'total'  => $row->total,
            ]);

        /* ==================== Omzet & Keuntungan Harian ==================== */
        $harianRaw = $baseQuery()
            ->selectRaw('DATE(created_at) as date, SUM(total_bayar) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $omzetHarian = $harianRaw->map(fn ($row) => [
            'date'  => Carbon::parse($row->date)->translatedFormat('d M'),
            'total' => (float) $row->total,
        ]);

        $keuntunganHarian = $harianRaw->map(fn ($row) => [
            'date'  => Carbon::parse($row->date)->translatedFormat('d M'),
            'total' => (float) $row->total,
        ]);

        return view('owner.laporan', compact(
            'preset', 'from', 'to',
            'totalOmzet', 'totalTransaksi', 'rataRataTransaksi',
            'totalKeuntungan', 'totalDiskon',
            'topMenu', 'kategoriBreakdown',
            'metodePembayaran', 'tipePesanan',
            'omzetHarian', 'keuntunganHarian'
        ));
    }
}