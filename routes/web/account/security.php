<?php

use App\Http\Controllers\Account\SecurityController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin.redirect'])->group(function () {
  Route::get('/akun/keamanan', [SecurityController::class, 'index'])->name('account.security.index');
  Route::delete('/akun/keamanan', [SecurityController::class, 'destroy'])->name('account.security.destroy');
});
