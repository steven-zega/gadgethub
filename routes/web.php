<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController; // IMPOR BARU: Untuk pesanan admin
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController; 
use App\Http\Controllers\CheckoutController; // Tambahan import untuk modul checkout

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');

Route::get('/dashboard', function () {
    if (!auth()->check()) {
        return redirect('/login');
    }

    if (auth()->user()->role == 'admin') {
        return redirect('/admin/dashboard');
    }

    return redirect('/user/dashboard');
})->middleware('auth');

// MODIFIKASI: Mengubah closure route untuk mengambil data statistik admin secara dinamis & real-time
Route::get('/admin/dashboard', function () {
    $adminId = auth()->id();

    // 1. Hitung jumlah produk nyata yang dimiliki oleh admin ini
    $totalProducts = \App\Models\Product::where('user_id', $adminId)->count();

    // 2. Hitung akumulasi pendapatan total dari transaksi sukses (status = success) milik admin ini
    $totalRevenue = \App\Models\Order::where('admin_id', $adminId)
        ->where('status', 'success')
        ->sum('total_price');

    // 3. Hitung jumlah pesanan masuk yang berstatus baru/menunggu (status = pending) milik admin ini
    $incomingOrders = \App\Models\Order::where('admin_id', $adminId)
        ->where('status', 'pending')
        ->count();

    return view('admin.dashboard', compact('totalProducts', 'totalRevenue', 'incomingOrders'));
})->middleware(['auth', 'admin']);

Route::get('/user/dashboard', [HomeController::class, 'index'])->middleware('auth')->name('user.dashboard');
Route::get('/user/products/{id}', [HomeController::class, 'show'])->middleware('auth')->name('user.products.show');

// MODIFIKASI: Menambahkan Group Route untuk Cart, Profile, Checkout, & Payment User
Route::middleware(['auth'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::delete('/cart/remove/{id}', [CartController::class, 'destroy'])->name('cart.remove'); 
    Route::post('/cart/add/{product}', [CartController::class, 'store'])->name('cart.add');
    Route::post('/cart/update/{id}', [CartController::class, 'updateQuantity'])->name('cart.update');

    Route::get('/user/profile', [HomeController::class, 'profile'])->name('user.profile');
    Route::put('/user/profile/update', [HomeController::class, 'updateProfile'])->name('user.profile.update');

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');

    Route::match(['get', 'post'], '/checkout/payment', [CheckoutController::class, 'payment'])->name('checkout.payment');
    Route::post('/checkout/payment/upload', [CheckoutController::class, 'uploadPayment'])->name('checkout.payment.upload');

    Route::get('/user/orders', [CheckoutController::class, 'orders'])->name('user.orders');
    
    Route::post('/user/orders/{id}/hide', [CheckoutController::class, 'hideOrder'])->name('user.orders.hide');
    Route::post('/user/orders/{id}/reorder', [CheckoutController::class, 'reorder'])->name('user.orders.reorder');
});

Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::resource('products', ProductController::class);
    
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');
    
    Route::patch('/orders/{id}/update-status', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');
});