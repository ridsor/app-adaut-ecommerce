<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post("/checkout", [ProductController::class, 'checkoutPost'])->name('product.checkout');

    Route::patch('/keranjang/{id}/quantity', [CartController::class, 'changeQuantity']);
});
