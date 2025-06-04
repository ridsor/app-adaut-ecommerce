<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::middleware('admin.redirect')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/pencarian', [SearchController::class, 'search'])->name('search');
    Route::get('/produk/{slug}', [ProductController::class, 'show'])->name("product.detail");
});

require __DIR__ . '/web/auth.php';
require __DIR__ . '/web/user/index.php';
require __DIR__ . '/web/admin/index.php';