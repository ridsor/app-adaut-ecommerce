<?php

use App\Http\Controllers\User\ReviewController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/user/pesanan/{order_number}/penilaian', [ReviewController::class, 'productIndex'])->name('user.review.product.index');
    Route::get('/user/pesanan/{order_number}/penilaian/{slug}', [ReviewController::class, 'productShow'])->name('user.review.product.show');
    Route::patch('/user/pesanan/{order_number}/penilaian/{slug}', [ReviewController::class, 'productUpdate'])->name('user.review.product.update');
});
