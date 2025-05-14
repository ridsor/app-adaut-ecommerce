<?php

use App\Http\Controllers\Admin\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware(['verified', 'auth'])->group(function () {
    Route::get('/produk/buat', [ProductController::class, 'create']);
    Route::post('/produk', [ProductController::class, 'store'])->name('product.store');
    Route::get('/produk/{id}/edit', [ProductController::class, 'edit']);
    Route::put('/produk/{id}', [ProductController::class, 'update'])->name('product.update');
    Route::delete('/produk/{id}', [ProductController::class, 'destroy'])->name('product.destroy');
});