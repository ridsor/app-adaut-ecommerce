<?php

use App\Http\Controllers\Account\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin.redirect'])->group(function () {


  Route::get('/akun/profil', [ProfileController::class, 'index'])->name('account.profile.index');
  Route::patch('/akun/profil', [ProfileController::class, 'update'])->name('account.profile.update');
});