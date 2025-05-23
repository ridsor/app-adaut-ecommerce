<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::middleware('admin.redirect')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/pencarian', [ProductController::class, 'search'])->name('search');
    Route::get('/produk/{slug}', [ProductController::class, 'show'])->name("product.detail");
    Route::post("/payment/notification-handler", [PaymentController::class, 'notificationHandler']);
});

require __DIR__.'/web/auth.php';
require __DIR__.'/web/profile.php';
require __DIR__.'/web/admin/index.php';