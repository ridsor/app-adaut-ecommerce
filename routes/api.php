<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post("/checkout", [ProductController::class, 'checkout'])->name('product.checkout');
});
