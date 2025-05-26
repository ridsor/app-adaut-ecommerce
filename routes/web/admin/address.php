<?php

use App\Http\Controllers\Admin\AddressController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware(['verified', 'auth'])->group(function () {
  Route::get('/akun/alamat', [AddressController::class, 'index'])->name('admin.address.index');
  Route::patch('/akun/alamat', [AddressController::class, 'update'])->name('admin.address.update');
});
