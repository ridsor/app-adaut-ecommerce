<?php

use App\Http\Controllers\Admin\BannerController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware(['verified', 'auth'])->group(function () {
    Route::get('/spaduk/buat', [BannerController::class, 'create'])->name('banner.create');
    Route::resource('/spanduk', BannerController::class)->names('banner')->except(['show','create']);
});