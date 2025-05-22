<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware('admin.redirect')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/pencarian', [ProductController::class, 'search'])->name('search');
    Route::get('/produk/{slug}', [ProductController::class, 'show'])->name("product.detail");
    Route::post("/payment/notification-handler", [PaymentController::class, 'notificationHandler']);
});


Route::middleware('auth')->group(function () {
    Route::get('/akun/profil', [ProfileController::class, 'index'])->name('profile.index');
    Route::patch('/akun/profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/akun/profil', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/web/auth.php';
require __DIR__.'/web/admin/index.php';