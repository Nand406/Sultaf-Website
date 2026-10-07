<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\TransaksiPenjualan;
use Carbon\Carbon;
use Illuminate\View\View;

class OwnerDashboardController extends Controller
{
    public function index(): View
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        /* ==================== REVENUE ==================== */
        $revenueToday = $this->verifiedQuery()->whereDate('created_at', $today)->sum('total_bayar');
        $revenueYesterday = $this->verifiedQuery()->whereDate('created_at', $yesterday)->sum('total_bayar');

        $revenueGrowth = $revenueYesterday > 0
            ? round((($revenueToday - $revenueYesterday) / $revenueYesterday) * 100, 1)
            : ($revenueToday > 0 ? 100 : 0);

        /* ==================== ORDERS ==================== */
        $totalOrdersToday = $this->verifiedQuery()->whereDate('created_at', $today)->count();
        $totalOrdersYesterday = $this->verifiedQuery()->whereDate('created_at', $yesterday)->count();

        $ordersGrowth = $totalOrdersYesterday > 0
            ? round((($totalOrdersToday - $totalOrdersYesterday) / $totalOrdersYesterday) * 100, 1)
            : ($totalOrdersToday > 0 ? 100 : 0);

        $avgOrderValue = $totalOrdersToday > 0 ? round($revenueToday / $totalOrdersToday) : 0;

        /* ==================== PROFIT, CUSTOMERS, PEAK HOUR ==================== */
        // Karena tidak ada kolom harga_modal di ERD baru, profit = revenue
        $profitToday = $revenueToday;
        $customersServed = $totalOrdersToday;

        $peakHour = $this->verifiedQuery()
            ->whereDate('created_at', $today)
            ->selectRaw('HOUR(created_at) as hour, COUNT(*) as total')
            ->groupBy('hour')
            ->orderByDesc('total')
            ->first();

        $peakHourLabel = $peakHour
            ? str_pad($peakHour->hour, 2, '0', STR_PAD_LEFT) . ':00'
            : '-';

        /* ==================== WEEKLY GROWTH ==================== */
        $revenueThisWeek = $this->verifiedQuery()
            ->whereBetween('created_at', [$today->copy()->subDays(7), $today])
            ->sum('total_bayar');

        $revenueLastWeek = $this->verifiedQuery()
            ->whereBetween('created_at', [$today->copy()->subDays(14), $today->copy()->subDays(7)])
            ->sum('total_bayar');

        $weeklyGrowth = $revenueLastWeek > 0
            ? round((($revenueThisWeek - $revenueLastWeek) / $revenueLastWeek) * 100, 1)
            : ($revenueThisWeek > 0 ? 100 : 0);

        /* ==================== RATING ==================== */
        // Tidak ada kolom rating di ERD baru
        $averageRating = 0;
        $totalMenuRated = 0;

        /* ==================== RECENT ORDERS ==================== */
        $recentOrders = TransaksiPenjualan::with('user')
            ->latest()
            ->limit(10)
            ->get();

        /* ==================== KITCHEN LOAD ==================== */
        $activeTickets = TransaksiPenjualan::whereIn('status_pesanan', ['pending', 'cooking'])->count();
        // Hitung load sebagai persentase dari kapasitas maksimum (misal 20 = penuh)
        $mainKitchenLoad = min(100, round(($activeTickets / 20) * 100));
        $avgPrepMinutes = 15; // Default estimasi (tidak ada kolom waktu prep)

        /* ==================== PRIORITY ORDER ==================== */
        $priorityOrder = TransaksiPenjualan::with(['user', 'detail_transaksi.menu'])
            ->whereIn('status_pesanan', ['pending', 'cooking'])
            ->oldest()
            ->first();

        /* ==================== REVENUE BY HOUR (CHART) ==================== */
        $revenueByHourRaw = $this->verifiedQuery()
            ->whereDate('created_at', $today)
            ->selectRaw('HOUR(created_at) as hour, SUM(total_bayar) as total')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get()
            ->keyBy('hour');

        $revenueByHour = collect();
        $profitByHour = collect();
        // Loop dari jam 8 pagi sampai 10 malam
        for ($h = 8; $h <= 22; $h++) {
            $revenueByHour->push([
                'label' => str_pad($h, 2, '0', STR_PAD_LEFT) . ':00',
                'value' => (float) ($revenueByHourRaw->get($h)->total ?? 0),
            ]);
            $profitByHour->push([
                'value' => (float) ($revenueByHourRaw->get($h)->total ?? 0),
            ]);
        }

        return view('owner.dashboard', compact(
            'revenueToday', 'revenueGrowth',
            'profitToday', 'customersServed', 'peakHourLabel',
            'weeklyGrowth', 'averageRating', 'totalMenuRated',
            'totalOrdersToday', 'ordersGrowth', 'avgOrderValue',
            'recentOrders', 'activeTickets', 'mainKitchenLoad', 'avgPrepMinutes',
            'priorityOrder', 'revenueByHour', 'profitByHour'
        ));
    }

    protected function verifiedQuery()
    {
        return TransaksiPenjualan::where('status_pembayaran', 'terverifikasi');
    }
}