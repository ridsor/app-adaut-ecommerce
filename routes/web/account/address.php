<?php

use App\Http\Controllers\Account\AddressController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin.redirect'])->group(function () {
  Route::get('/akun/alamat', [AddressController::class, 'index'])->name('account.address.index');
  Route::patch('/akun/alamat', [AddressController::class, 'update'])->name('account.address.update');
});
