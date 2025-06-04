<?php

use App\Http\Controllers\Api\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Api\User\OrderController as UserOrderConttroller;
use App\Http\Controllers\Api\User\CheckoutController;
use App\Http\Controllers\Api\PaymentNotification;
use App\Http\Controllers\Api\RajaOngkirController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum', 'verified')->group(function () {
    Route::get("/address/search-destination", [RajaOngkirController::class, 'searchDestination'])->name('api.address.search-destination');
    Route::post("/address/domestic-cost", [RajaOngkirController::class, 'domesticCost'])->name('api.address.domestic-cost');
});

// user
Route::post("/payments/notifications", PaymentNotification::class);
Route::middleware('auth:sanctum', 'verified')->group(function () {
    Route::post("/product/checkout", [CheckoutController::class, 'productCheckout'])->name('api.product.checkout.store');
    Route::patch("/pesanan/status", [UserOrderConttroller::class, 'update'])->name('api.user.order.update');
});

// admin
Route::prefix('admin')->middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::patch("/pesanan/status", [AdminOrderController::class, 'update'])->name('api.admin.order.update');
});
