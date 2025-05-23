<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin.redirect'])->group(function () {
  Route::get('/akun/profil', [ProfileController::class, 'index'])->name('profile.index');
  Route::patch('/akun/profil', [ProfileController::class, 'update'])->name('profile.update');
  Route::delete('/akun/profil', [ProfileController::class, 'destroy'])->name('profile.destroy');
});