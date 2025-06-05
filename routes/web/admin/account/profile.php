<?php

use App\Http\Controllers\Admin\Account\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
  Route::get('/admin/akun/profil', [ProfileController::class, 'index'])->name('admin.account.profile.index');
  Route::patch('/admin/akun/profil', [ProfileController::class, 'update'])->name('admin.account.profile.update');
});
