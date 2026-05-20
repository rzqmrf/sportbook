<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LapanganController;
use App\Http\Controllers\Admin\ReservasiController;

// ─── PUBLIC ROUTES ────────────────────────────────────────────────────────────
Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/reservasi', [LandingController::class, 'reservasi'])->name('reservasi');
Route::post('/reservasi', [LandingController::class, 'storeReservasi'])->name('reservasi.store');

// ─── ADMIN AUTH ───────────────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Protected admin routes
    Route::middleware('admin.auth')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Lapangan
        Route::get('/lapangan', [LapanganController::class, 'index'])->name('lapangan.index');
        Route::get('/lapangan/tambah', [LapanganController::class, 'create'])->name('lapangan.create');
        Route::post('/lapangan', [LapanganController::class, 'store'])->name('lapangan.store');
        Route::get('/lapangan/{lapangan}', [LapanganController::class, 'show'])->name('lapangan.show');
        Route::get('/lapangan/{lapangan}/edit', [LapanganController::class, 'edit'])->name('lapangan.edit');
        Route::put('/lapangan/{lapangan}', [LapanganController::class, 'update'])->name('lapangan.update');
        Route::delete('/lapangan/{lapangan}', [LapanganController::class, 'destroy'])->name('lapangan.destroy');
        Route::post('/lapangan/{lapangan}/toggle', [LapanganController::class, 'toggleStatus'])->name('lapangan.toggle');

        // Reservasi
        Route::get('/reservasi', [ReservasiController::class, 'index'])->name('reservasi.index');
        Route::get('/reservasi/{reservasi}', [ReservasiController::class, 'show'])->name('reservasi.show');
        Route::post('/reservasi/{reservasi}/approve', [ReservasiController::class, 'approve'])->name('reservasi.approve');
        Route::post('/reservasi/{reservasi}/reject', [ReservasiController::class, 'reject'])->name('reservasi.reject');
        Route::delete('/reservasi/{reservasi}', [ReservasiController::class, 'destroy'])->name('reservasi.destroy');
    });
});
