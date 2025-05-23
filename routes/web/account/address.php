<?php

use App\Http\Controllers\Account\AddressController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin.redirect'])->group(function () {
  Route::resource('/akun/alamat', AddressController::class)->names('account.address');
});