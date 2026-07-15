<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\TransaksiItem;
use App\Models\TransaksiPenjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class LaporanController extends Controller
{
    public function index(Request $request): View
    {
        $preset = $request->get('preset', '30hari');
        [$from, $to] = $this->resolveDateRange($preset, $request->get('from'), $request->get('to'));

        $baseQuery = fn () => TransaksiPenjualan::where('status_pembayaran', 'terverifikasi')
            ->whereBetween('tgl_transaksi', [$from->copy()->startOfDay(), $to->copy()->endOfDay()]);

        /* ===================== Ringkasan ===================== */
        $transaksi = $baseQuery()->get();

        $totalOmzet = $transaksi->sum('total_harga');
        $totalTransaksi = $transaksi->count();
        $rataRataTransaksi = $totalTransaksi > 0 ? $totalOmzet / $totalTransaksi : 0;
        $totalDiskon = $transaksi->sum('diskon_member');

        /* ===================== Item terjual (dasar keuntungan, breakdown kategori & top menu) ===================== */
        // 'transaksi' ikut di-eager-load supaya bisa dikelompokkan per tanggal untuk grafik.
        $itemQuery = TransaksiItem::whereHas('transaksi', fn ($q) => $q
            ->where('status_pembayaran', 'terverifikasi')
            ->whereBetween('tgl_transaksi', [$from->copy()->startOfDay(), $to->copy()->endOfDay()])
        )->with(['menu.category', 'transaksi']);

        $items = $itemQuery->get();

        // Keuntungan per item = qty x (harga jual saat transaksi - harga modal menu saat ini)
        $hitungKeuntunganItem = fn ($i) => $i->qty * ((float) $i->harga_satuan - (float) ($i->menu->harga_modal ?? 0));

        $totalKeuntungan = $items->sum($hitungKeuntunganItem);

        /* ===================== Grafik Omzet & Keuntungan per Hari ===================== */
        $omzetHarian = $transaksi
            ->groupBy(fn ($t) => $t->tgl_transaksi->format('Y-m-d'))
            ->map(fn ($group, $date) => [
                'date' => Carbon::parse($date)->translatedFormat('d M'),
                'total' => (float) $group->sum('total_harga'),
            ])
            ->sortKeys()
            ->values();

        $keuntunganHarian = $items
            ->filter(fn ($i) => $i->transaksi !== null)
            ->groupBy(fn ($i) => $i->transaksi->tgl_transaksi->format('Y-m-d'))
            ->map(fn ($group, $date) => [
                'date' => Carbon::parse($date)->translatedFormat('d M'),
                'total' => (float) $group->sum($hitungKeuntunganItem),
            ])
            ->sortKeys()
            ->values();

        /* ===================== Top Menu & Breakdown Kategori ===================== */
        $topMenu = $items
            ->groupBy('menu_id')
            ->map(function ($group) use ($hitungKeuntunganItem) {
                $menu = $group->first()->menu;
                return [
                    'menu' => $menu,
                    'qty' => $group->sum('qty'),
                    'revenue' => $group->sum(fn ($i) => $i->qty * $i->harga_satuan),
                    'keuntungan' => $group->sum($hitungKeuntunganItem),
                ];
            })
            ->filter(fn ($row) => $row['menu'] !== null)
            ->sortByDesc('qty')
            ->take(8)
            ->values();

        $kategoriBreakdown = $items
            ->filter(fn ($i) => $i->menu !== null)
            ->groupBy(fn ($i) => $i->menu->category->name ?? 'Lainnya')
            ->map(fn ($group, $nama) => [
                'kategori' => $nama,
                'revenue' => $group->sum(fn ($i) => $i->qty * $i->harga_satuan),
            ])
            ->sortByDesc('revenue')
            ->values();

        /* ===================== Metode Pembayaran ===================== */
        $metodePembayaran = $transaksi
            ->groupBy(fn ($t) => $t->metode_pembayaran ?? '-')
            ->map(fn ($group, $metode) => [
                'metode' => $metode,
                'jumlah' => $group->count(),
                'total' => $group->sum('total_harga'),
            ])
            ->sortByDesc('total')
            ->values();

        /* ===================== Tipe Pesanan (Dine-in/Takeaway) ===================== */
        $tipePesanan = $transaksi
            ->groupBy('tipe_pesanan')
            ->map(fn ($group, $tipe) => [
                'tipe' => $tipe,
                'jumlah' => $group->count(),
                'total' => $group->sum('total_harga'),
            ])
            ->sortByDesc('total')
            ->values();

        return view('owner.laporan', [
            'from' => $from,
            'to' => $to,
            'preset' => $preset,
            'totalOmzet' => $totalOmzet,
            'totalKeuntungan' => $totalKeuntungan,
            'totalTransaksi' => $totalTransaksi,
            'rataRataTransaksi' => $rataRataTransaksi,
            'totalDiskon' => $totalDiskon,
            'omzetHarian' => $omzetHarian,
            'keuntunganHarian' => $keuntunganHarian,
            'topMenu' => $topMenu,
            'kategoriBreakdown' => $kategoriBreakdown,
            'metodePembayaran' => $metodePembayaran,
            'tipePesanan' => $tipePesanan,
        ]);
    }

    protected function resolveDateRange(string $preset, ?string $from, ?string $to): array
    {
        $now = Carbon::now();

        return match ($preset) {
            'hari-ini' => [$now->copy()->startOfDay(), $now->copy()->endOfDay()],
            '7hari' => [$now->copy()->subDays(6)->startOfDay(), $now->copy()->endOfDay()],
            'bulan-ini' => [$now->copy()->startOfMonth(), $now->copy()->endOfDay()],
            'tahun-ini' => [$now->copy()->startOfYear(), $now->copy()->endOfDay()],
            'custom' => [
                $from ? Carbon::parse($from) : $now->copy()->subDays(29),
                $to ? Carbon::parse($to) : $now->copy(),
            ],
            default => [$now->copy()->subDays(29)->startOfDay(), $now->copy()->endOfDay()], // 30hari
        };
    }
}
