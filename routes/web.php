<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SoalController;
use App\Http\Controllers\MataKuliahController;
use App\Http\Controllers\KuisController;

// Halaman utama redirect ke login
Route::get('/', [LoginController::class, 'showLoginForm'])->name('home');

// Route autentikasi (tanpa middleware)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Route yang membutuhkan autentikasi
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Soal
    Route::resource('soal', SoalController::class)->only(['index', 'create', 'store']);
    Route::get('/soal/{soal}/edit', [SoalController::class, 'edit'])->name('soal.edit');

    // Mata Kuliah
    Route::resource('mata-kuliah', MataKuliahController::class)->except(['destroy']);
    Route::delete('/mata-kuliah/{mata_kuliah}', [MataKuliahController::class, 'destroy'])->name('mata_kuliah.delete');

    // Kuis
    Route::get('/kuis', [KuisController::class, 'index'])->name('kuis.index');
    Route::get('/kuis/{mataKuliahId}/mulai', [KuisController::class, 'mulai'])->name('kuis.mulai');
    Route::get('/kuis/{kuisId}/soal/{index}', [KuisController::class, 'soal'])->name('kuis.soal');
    Route::post('/kuis/{kuisId}/soal/{index}', [KuisController::class, 'simpanJawaban'])->name('kuis.simpanJawaban');
    Route::get('/kuis/{kuisId}/hasil', [KuisController::class, 'hasil'])->name('kuis.hasil');
});
