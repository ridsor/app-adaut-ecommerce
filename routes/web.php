<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::middleware('admin.redirect')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/pencarian', [ProductController::class, 'search'])->name('search');
    Route::get('/checkout', [CheckoutController::class, 'produkCheckout'])->name('checkout');
    Route::get('/produk/{slug}', [ProductController::class, 'show'])->name("product.detail");
});

require __DIR__ . '/web/auth.php';
require __DIR__ . '/web/user/index.php';
require __DIR__ . '/web/admin/index.php';
