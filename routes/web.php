<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [ProductController::class, 'search'])->name('search');
Route::get('/produk', [ProductController::class, 'index']);
Route::get('/produk/{id}', [ProductController::class, 'show']);
Route::post("/payment/notification-handler", [PaymentController::class, 'notificationHandler']);
Route::group(['middleware' => ['verified', 'auth']], function () {
    Route::get('/checkout', [ProductController::class, 'checkout']);
    
    Route::get('/produk/buat', [ProductController::class, 'create']);
    Route::post('/produk', [ProductController::class, 'store'])->name('product.store');
    Route::get('/produk/{id}/edit', [ProductController::class, 'edit']);
    Route::put('/produk/{id}', [ProductController::class, 'update'])->name('product.update');
    Route::delete('/produk/{id}', [ProductController::class, 'destroy'])->name('product.destroy');

    Route::post('/keranjang', [CartController::class, 'store']);
    Route::delete('/keranjang/{id}', [CartController::class, 'destroy']);
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
