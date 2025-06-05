<?php

use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware(['verified', 'auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

require __DIR__ . '/order.php';
require __DIR__ . '/product.php';
require __DIR__ . '/category.php';
require __DIR__ . '/banner.php';
require __DIR__ . '/address.php';
require __DIR__ . '/account/index.php';