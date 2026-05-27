<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController; // Tambahan import untuk modul keranjang belanja

// Redirect halaman utama ke login
Route::get('/', function () {
    return redirect('/login');
});

// Autentikasi
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');

// Gatekeeper Dashboard (Penentu arah berdasarkan role)
Route::get('/dashboard', function () {
    if (!auth()->check()) {
        return redirect('/login');
    }

    if (auth()->user()->role == 'admin') {
        return redirect('/admin/dashboard');
    }

    return redirect('/user/dashboard');
})->middleware('auth');

// Halaman Utama Admin
Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->middleware(['auth', 'admin']);

// Route untuk User (Dashboard & Detail Produk)
Route::get('/user/dashboard', [HomeController::class, 'index'])->middleware('auth')->name('user.dashboard');
Route::get('/user/products/{id}', [HomeController::class, 'show'])->middleware('auth')->name('user.products.show');

// MODIFIKASI: Menambahkan Group Route untuk Cart (Keranjang Belanja) User
Route::middleware(['auth'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'store'])->name('cart.add');
    Route::post('/cart/update/{id}', [CartController::class, 'updateQuantity'])->name('cart.update');
});

// Group Route CRUD Admin (Sudah diberi pengaman middleware auth dan admin)
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::resource('products', ProductController::class);
});