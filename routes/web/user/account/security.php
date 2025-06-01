<?php

use App\Http\Controllers\User\Account\SecurityController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin.redirect'])->group(function () {
  Route::get('/pengguna/akun/keamanan', [SecurityController::class, 'index'])->name('user.account.security.index');
  Route::delete('/pengguna/akun/keamanan', [SecurityController::class, 'destroy'])->name('user.account.security.destroy');
});
