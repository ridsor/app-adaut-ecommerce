<?php

use App\Http\Controllers\Admin\OrderController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware(['verified', 'auth'])->group(function () {
    Route::get('/pesanan', [OrderController::class, 'index'])->name('admin.order.index');
    Route::get('/pesanan/{order_number}', [OrderController::class, 'show'])->name('admin.order.show');
});
