<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\BenefitController;
use App\Http\Controllers\Admin\MenuManagementController;
use App\Http\Controllers\Admin\PromoMessageController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Dapur\DapurDashboardController;
use App\Http\Controllers\Dapur\KitchenDisplayController;
use App\Http\Controllers\Dapur\MenuAvailabilityController as DapurMenuAvailabilityController;
use App\Http\Controllers\Kasir\KasirDashboardController;
use App\Http\Controllers\Kasir\PembayaranController;
use App\Http\Controllers\Kasir\PesananController as KasirPesananController;
use App\Http\Controllers\Kasir\PosController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Owner\LaporanController;
use App\Http\Controllers\Owner\OwnerDashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RewardsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Ganti Bahasa (ID/EN)
|--------------------------------------------------------------------------
*/
Route::get('/lang/{locale}', [LocaleController::class, 'switch'])->name('lang.switch');

/*
|--------------------------------------------------------------------------
| Halaman Utama — Daftar Menu (boleh diakses tanpa login)
|--------------------------------------------------------------------------
*/
Route::get('/', [MenuController::class, 'index'])->name('menu.index');

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Cart & Checkout (Customer)
|--------------------------------------------------------------------------
*/
Route::post('/cart/{menu}/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/{menu}/decrease', [CartController::class, 'decrease'])->name('cart.decrease');
Route::delete('/cart/{menu}', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');

Route::prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/details', [CheckoutController::class, 'details'])->name('details');
    Route::post('/details', [CheckoutController::class, 'storeDetails'])->name('details.store');
    Route::get('/payment', [CheckoutController::class, 'payment'])->name('payment');
    Route::post('/payment', [CheckoutController::class, 'storePayment'])->name('payment.store');
    Route::get('/finish/{transaksi}', [CheckoutController::class, 'finish'])->name('finish');
});

/*
|--------------------------------------------------------------------------
| Area Member (butuh login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/rewards', [RewardsController::class, 'index'])->name('rewards.index');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{transaksi}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});

/*
|--------------------------------------------------------------------------
| Area Kasir
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:kasir,admin,owner'])->prefix('kasir')->name('kasir.')->group(function () {
    Route::get('/', [KasirDashboardController::class, 'index'])->name('dashboard');
    Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran');
    Route::post('/pembayaran/{transaksi}/verify', [PembayaranController::class, 'verify'])->name('pembayaran.verify');
    Route::post('/pembayaran/{transaksi}/reject', [PembayaranController::class, 'reject'])->name('pembayaran.reject');
    Route::get('/pesanan', [KasirPesananController::class, 'index'])->name('pesanan');
    Route::post('/pesanan/{transaksi}/update', [KasirPesananController::class, 'updateStatus'])->name('pesanan.update');
    Route::get('/pos', [PosController::class, 'index'])->name('pos');
    Route::post('/pos/{menu}/add', [PosController::class, 'addItem'])->name('pos.add');
    Route::post('/pos/{menu}/decrease', [PosController::class, 'decreaseItem'])->name('pos.decrease');
    Route::delete('/pos/{menu}', [PosController::class, 'removeItem'])->name('pos.remove');
    Route::post('/pos/clear', [PosController::class, 'clearCart'])->name('pos.clear');
    Route::post('/pos/checkout', [PosController::class, 'checkout'])->name('pos.checkout');
    Route::get('/pos/receipt/{transaksi}', [PosController::class, 'receipt'])->name('pos.receipt');
});

/*
|--------------------------------------------------------------------------
| Area Dapur
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:dapur,admin,owner'])->prefix('dapur')->name('dapur.')->group(function () {
    Route::get('/', [DapurDashboardController::class, 'index'])->name('dashboard');
    Route::get('/pesanan', [KitchenDisplayController::class, 'index'])->name('pesanan');
    Route::post('/pesanan/{transaksi}/update', [KitchenDisplayController::class, 'updateStatus'])->name('pesanan.update');
    Route::get('/menu', [DapurMenuAvailabilityController::class, 'index'])->name('menu.index');
    Route::post('/menu/{menu}/toggle', [DapurMenuAvailabilityController::class, 'toggle'])->name('menu.toggle');
});

/*
|--------------------------------------------------------------------------
| Area Admin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin,owner'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/menu', [MenuManagementController::class, 'index'])->name('menu.index');
    Route::get('/menu/create', [MenuManagementController::class, 'create'])->name('menu.create');
    Route::post('/menu', [MenuManagementController::class, 'store'])->name('menu.store');
    Route::get('/menu/{menu}/edit', [MenuManagementController::class, 'edit'])->name('menu.edit');
    Route::put('/menu/{menu}', [MenuManagementController::class, 'update'])->name('menu.update');
    Route::delete('/menu/{menu}', [MenuManagementController::class, 'destroy'])->name('menu.destroy');
    Route::post('/menu/{menu}/toggle', [MenuManagementController::class, 'toggleHabis'])->name('menu.toggle');

    Route::get('/benefit', [BenefitController::class, 'index'])->name('benefit.index');
    Route::get('/benefit/create', [BenefitController::class, 'create'])->name('benefit.create');
    Route::post('/benefit', [BenefitController::class, 'store'])->name('benefit.store');
    Route::get('/benefit/{benefit}/edit', [BenefitController::class, 'edit'])->name('benefit.edit');
    Route::put('/benefit/{benefit}', [BenefitController::class, 'update'])->name('benefit.update');
    Route::delete('/benefit/{benefit}', [BenefitController::class, 'destroy'])->name('benefit.destroy');
    Route::post('/benefit/{benefit}/toggle', [BenefitController::class, 'toggle'])->name('benefit.toggle');

    Route::get('/promo', [PromoMessageController::class, 'index'])->name('promo.index');
    Route::post('/promo', [PromoMessageController::class, 'store'])->name('promo.store');
    Route::delete('/promo/{promo}', [PromoMessageController::class, 'destroy'])->name('promo.destroy');
});

/*
|--------------------------------------------------------------------------
| Area Owner — dashboard analitik & laporan penjualan/keuangan
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:owner,admin'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/', [OwnerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');
});
