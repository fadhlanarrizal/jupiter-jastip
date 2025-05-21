<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
});


Route::middleware(['auth'])->group(function () {
    Route::view('/pesan', 'user.pesan')->name('pesan.form');
    Route::post('/pesan', [OrderController::class, 'store'])->name('pesan.store');
    Route::get('/riwayat', [OrderController::class, 'index'])->name('pesan.riwayat');

    Route::get('/pesan/{order}/edit', [OrderController::class, 'edit'])->name('pesan.edit');
    Route::put('/pesan/{order}', [OrderController::class, 'update'])->name('pesan.update');
    
});

Route::get('/dashboard', [OrderController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/dashboard/export-pdf', [OrderController::class, 'exportPdf'])->name('dashboard.export');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
