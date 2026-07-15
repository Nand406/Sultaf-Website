<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\TransaksiItem;
use App\Models\TransaksiPenjualan;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class OwnerDashboardController extends Controller
{
    public function index(): View
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        /* ===================== Today's Revenue + growth vs kemarin ===================== */
        $revenueToday = $this->verifiedQuery()->whereDate('tgl_transaksi', $today)->sum('total_harga');
        $revenueYesterday = $this->verifiedQuery()->whereDate('tgl_transaksi', $yesterday)->sum('total_harga');
        $revenueGrowth = $revenueYesterday > 0
            ? round((($revenueToday - $revenueYesterday) / $revenueYesterday) * 100, 1)
            : ($revenueToday > 0 ? 100 : 0);

        /* ===================== Weekly Growth (7 hari terakhir vs 7 hari sebelumnya) ===================== */
        $last7Days = $this->verifiedQuery()
            ->whereBetween('tgl_transaksi', [Carbon::now()->subDays(7), Carbon::now()])
            ->sum('total_harga');
        $prev7Days = $this->verifiedQuery()
            ->whereBetween('tgl_transaksi', [Carbon::now()->subDays(14), Carbon::now()->subDays(7)])
            ->sum('total_harga');
        $weeklyGrowth = $prev7Days > 0
            ? round((($last7Days - $prev7Days) / $prev7Days) * 100, 1)
            : ($last7Days > 0 ? 100 : 0);

        /* ===================== Customers Served + Peak Hour (hari ini) ===================== */
        $ordersToday = $this->verifiedQuery()->whereDate('tgl_transaksi', $today)->get();
        $customersServed = $ordersToday->count();
        $peakHour = $ordersToday
            ->groupBy(fn ($o) => $o->tgl_transaksi->format('H'))
            ->sortByDesc(fn ($g) => $g->count())
            ->keys()
            ->first();
        $peakHourLabel = $peakHour !== null
            ? sprintf('%02d:00 - %02d:00', $peakHour, ((int) $peakHour + 1) % 24)
            : '-';

        /* ===================== Average Rating ===================== */
        $averageRating = round(Menu::where('rating', '>', 0)->avg('rating') ?? 0, 1);
        $totalMenuRated = Menu::where('rating', '>', 0)->count();

        /* ===================== Recent Orders ===================== */
        $recentOrders = TransaksiPenjualan::with('user')
            ->latest('tgl_transaksi')
            ->limit(6)
            ->get();

        /* ===================== Kitchen Load ===================== */
        $activeTickets = TransaksiPenjualan::whereIn('status_pesanan', ['pending', 'cooking', 'ready', 'diantar'])->count();
        $cookingCount = TransaksiPenjualan::where('status_pesanan', 'cooking')->count();
        $kitchenCapacity = 15; // asumsi kapasitas dapur normal, dipakai sbg pembagi persentase beban
        $mainKitchenLoad = min(100, round(($activeTickets / $kitchenCapacity) * 100));

        $avgPrepMinutes = TransaksiPenjualan::where('status_pesanan', 'selesai')
            ->whereDate('updated_at', $today)
            ->get()
            ->avg(fn ($t) => $t->tgl_transaksi->diffInMinutes($t->updated_at));
        $avgPrepMinutes = $avgPrepMinutes ? round($avgPrepMinutes) : 0;

        /* ===================== Priority Order Spotlight ===================== */
        $priorityOrder = TransaksiPenjualan::with('items.menu')
            ->whereIn('status_pesanan', ['pending', 'cooking'])
            ->where('status_pembayaran', 'terverifikasi')
            ->oldest('tgl_transaksi')
            ->first();

        /* ===================== Revenue & Profit Momentum (per 2 jam, hari ini) ===================== */
        // Diambil dari item transaksi (bukan sekadar total transaksi) supaya
        // keuntungan bisa dihitung: qty x (harga jual - harga modal per menu).
        $itemsToday = TransaksiItem::whereHas('transaksi', fn ($q) => $q
                ->where('status_pembayaran', 'terverifikasi')
                ->whereDate('tgl_transaksi', $today)
            )
            ->with(['transaksi:id,tgl_transaksi', 'menu:id,harga_modal'])
            ->get();

        $profitToday = $itemsToday->sum(fn ($i) => $i->qty * ((float) $i->harga_satuan - (float) ($i->menu->harga_modal ?? 0)));

        $revenueByHour = [];
        $profitByHour = [];
        for ($h = 8; $h <= 23; $h += 2) {
            $bucket = $itemsToday->filter(function ($i) use ($h) {
                $hour = (int) $i->transaksi->tgl_transaksi->format('H');
                return $hour >= $h && $hour < $h + 2;
            });

            $revenueByHour[] = [
                'label' => sprintf('%02d:00', $h),
                'value' => (float) $bucket->sum(fn ($i) => $i->qty * $i->harga_satuan),
            ];
            $profitByHour[] = [
                'label' => sprintf('%02d:00', $h),
                'value' => (float) $bucket->sum(fn ($i) => $i->qty * ((float) $i->harga_satuan - (float) ($i->menu->harga_modal ?? 0))),
            ];
        }

        return view('owner.dashboard', [
            'revenueToday' => $revenueToday,
            'revenueGrowth' => $revenueGrowth,
            'profitToday' => $profitToday,
            'weeklyGrowth' => $weeklyGrowth,
            'customersServed' => $customersServed,
            'peakHourLabel' => $peakHourLabel,
            'averageRating' => $averageRating,
            'totalMenuRated' => $totalMenuRated,
            'recentOrders' => $recentOrders,
            'activeTickets' => $activeTickets,
            'cookingCount' => $cookingCount,
            'mainKitchenLoad' => $mainKitchenLoad,
            'avgPrepMinutes' => $avgPrepMinutes,
            'priorityOrder' => $priorityOrder,
            'revenueByHour' => $revenueByHour,
            'profitByHour' => $profitByHour,
        ]);
    }

    protected function verifiedQuery()
    {
        return TransaksiPenjualan::where('status_pembayaran', 'terverifikasi');
    }
}
