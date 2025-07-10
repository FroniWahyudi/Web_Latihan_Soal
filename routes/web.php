<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SoalController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MataKuliahController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LoginController::class, 'showLoginForm'])->name('home');

// Route autentikasi
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Route yang dilindungi oleh middleware auth dan verified
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Soal
    Route::resource('soal', SoalController::class)->only([
        'index', 'create', 'store', 'edit'
    ]);

    // Mata Kuliah
    Route::resource('mata-kuliah', MataKuliahController::class)->except(['destroy']);
    Route::delete('/mata-kuliah/{mata_kuliah}', [MataKuliahController::class, 'destroy'])->name('mata_kuliah.delete');
});