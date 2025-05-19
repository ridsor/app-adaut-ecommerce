<?php

use App\Http\Controllers\Admin\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware(['verified', 'auth'])->group(function () {
    Route::get('/produk/buat', [ProductController::class, 'create'])->name('product.create');
    Route::resource('/produk', ProductController::class)->except(['create'])->names('product');
});