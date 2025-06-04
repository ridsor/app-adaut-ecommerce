<?php

use App\Http\Controllers\User\CheckoutController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
  Route::get('/checkout', [CheckoutController::class, 'produkCheckout'])->name('checkout');
});

require __DIR__ . '/account/index.php';
require __DIR__ . '/order.php';
require __DIR__ . '/review.php';
