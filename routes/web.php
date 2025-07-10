<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SoalController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MataKuliahController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Soal
    Route::get('/soal', [SoalController::class, 'index'])->name('soal.index');
    Route::get('/soal/create', [SoalController::class, 'create'])->name('soal.create');
    Route::post('/soal', [SoalController::class, 'store'])->name('soal.store');
    Route::get('/edit-soal/{id}', [SoalController::class, 'edit'])->name('soal.edit');

    // Mata Kuliah
    Route::delete('/mata-kuliah/{id}', [MataKuliahController::class, 'destroy'])->name('mata_kuliah.delete');
    // Tambahkan juga route lainnya jika diperlukan
    Route::get('/mata-kuliah', [MataKuliahController::class, 'index'])->name('mata_kuliah.index');
});
