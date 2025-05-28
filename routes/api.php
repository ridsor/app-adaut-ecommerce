<?php

use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\PaymentNotification;
use App\Http\Controllers\Api\RajaOngkirController;
use Illuminate\Support\Facades\Route;

Route::post("/payments/notifications", PaymentNotification::class);
Route::middleware('auth:sanctum')->group(function () {
    Route::post("/product/checkout", [CheckoutController::class, 'productCheckout'])->name('product.checkout.store');
    Route::get("/address/search-destination", [RajaOngkirController::class, 'searchDestination'])->name('address.search-destination');
    Route::post("/address/domestic-cost", [RajaOngkirController::class, 'domesticCost'])->name('address.domestic-cost');
});
