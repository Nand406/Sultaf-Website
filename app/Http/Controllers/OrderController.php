<?php

namespace App\Http\Controllers;

use App\Models\TransaksiPenjualan;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        $orders = TransaksiPenjualan::where('user_id', Auth::id())
            ->latest('tgl_transaksi')
            ->get();

        return view('orders.index', compact('orders'));
    }

    public function show(TransaksiPenjualan $transaksi): View
    {
        $transaksi->load('items.menu');

        return view('orders.show', compact('transaksi'));
    }
}
