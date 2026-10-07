<?php

namespace App\Http\Controllers;

use App\Models\TransaksiPenjualan;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        // DIUBAH: 'user_id' -> 'id_user', 'tgl_transaksi' -> 'created_at'
        $orders = TransaksiPenjualan::where('id_user', Auth::id())
            ->with('detail_transaksi.menu')
            ->latest() // Otomatis pakai created_at
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function show(TransaksiPenjualan $transaksi): View
    {
        // Pastikan user hanya bisa melihat transaksi miliknya sendiri
        if ($transaksi->id_user !== Auth::id()) {
            abort(403);
        }

        // DIUBAH: 'items.menu' -> 'detail_transaksi.menu'
        $transaksi->load(['detail_transaksi.menu', 'user']);

        return view('orders.show', compact('transaksi'));
    }
}