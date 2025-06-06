<?php

use App\Http\Controllers\Admin\OrderController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware(['verified', 'auth'])->group(function () {
    Route::get('/pesanan', [OrderController::class, 'index'])->name('admin.order.index');
    Route::get('/pesanan/{pesanan}', [OrderController::class, 'show'])->name('admin.order.show');
    Route::patch('/pesanan/{pesanan}', [OrderController::class, 'updateAwb'])->name('admin.order.update.awb');
});
