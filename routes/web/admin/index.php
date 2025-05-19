<?php

use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware(['verified', 'auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

require __DIR__.'/product.php';
require __DIR__.'/banner.php';