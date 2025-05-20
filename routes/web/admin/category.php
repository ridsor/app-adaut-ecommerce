<?php

use App\Http\Controllers\Admin\CategoryController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware(['verified', 'auth'])->group(function () {
    Route::get('/kategori/buat', [CategoryController::class, 'create'])->name('category.create');
    Route::resource('/kategori', CategoryController::class)->except(['create', 'show'])->names('category');
});
