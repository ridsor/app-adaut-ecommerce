<?php

use App\Http\Controllers\User\Account\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin.redirect'])->group(function () {
  Route::get('/pengguna/akun/profil', [ProfileController::class, 'index'])->name('user.account.profile.index');
  Route::patch('/pengguna/akun/profil', [ProfileController::class, 'update'])->name('user.account.profile.update');
});