<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\MataKuliah;

Route::get('/', function () {
    return view('dashboard'); // dashboard.blade.php
})->middleware(['auth', 'verified'])->name('dashboard');

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Contoh route tambahan (jika ada halaman buat/edit soal)
    Route::view('/buat-soal', 'buat_soal')->name('soal.buat');
    Route::view('/edit-soal', 'edit_soal')->name('soal.edit');
});

// Include auth (login, register, etc.)
require __DIR__.'/auth.php';


Route::get('/', function () {
    $subjects = MataKuliah::all(); // ambil semua data mata kuliah
    return view('dashboard', compact('subjects'));
})->middleware(['auth', 'verified'])->name('dashboard');
