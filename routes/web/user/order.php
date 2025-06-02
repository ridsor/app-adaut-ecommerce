<?php

use App\Http\Controllers\User\OrderController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/pengguna/pesanan', [OrderController::class, 'index'])->name('user.order.index');
    Route::get('/pengguna/pesanan/belum-bayar', [OrderController::class, 'unpaid'])->name('user.order.unpaid');
    Route::get('/pengguna/pesanan/dikemas', [OrderController::class, 'packed'])->name('user.order.packed');
    Route::get('/pengguna/pesanan/dikirim', [OrderController::class, 'submitted'])->name('user.order.submitted');
    Route::get('/pengguna/pesanan/selesai', [OrderController::class, 'completed'])->name('user.order.completed');
    Route::get('/pengguna/pesanan/dibatalkan', [OrderController::class, 'failed'])->name('user.order.failed');
    Route::get('/pengguna/pesanan/{order_number}', [OrderController::class, 'show'])->name('user.order.show');
});
