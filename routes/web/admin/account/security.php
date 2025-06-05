<?php

use App\Http\Controllers\Admin\Account\SecurityController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
  Route::get('/admin/akun/keamanan', [SecurityController::class, 'index'])->name('admin.account.security.index');
  Route::delete('/admin/akun/keamanan', [SecurityController::class, 'destroy'])->name('admin.account.security.destroy');
});
