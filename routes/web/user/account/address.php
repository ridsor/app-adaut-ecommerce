<?php

use App\Http\Controllers\User\Account\AddressController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin.redirect'])->group(function () {
  Route::get('/pengguna/akun/alamat', [AddressController::class, 'index'])->name('user.account.address.index');
  Route::patch('/pengguna/akun/alamat', [AddressController::class, 'update'])->name('user.account.address.update');
});
