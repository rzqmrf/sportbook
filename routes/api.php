<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;

/*
|--------------------------------------------------------------------------
| API Routes — SportBook
|--------------------------------------------------------------------------
| Base URL  : http://localhost:8000/api
| Auth      : Sanctum Bearer Token
|--------------------------------------------------------------------------
*/

// ─── PUBLIC (tanpa token) ─────────────────────────────────────────────────────

// Auth
Route::post('/login', [ApiController::class, 'login']);

// Lapangan publik
Route::get('/lapangan',      [ApiController::class, 'lapanganIndex']);
Route::get('/lapangan/{lapangan}', [ApiController::class, 'lapanganShow']);

// Reservasi publik (buat booking baru)
Route::post('/reservasi', [ApiController::class, 'reservasiStore']);
Route::post('/reservasi/track', [ApiController::class, 'reservasiTrack']);


// ─── PROTECTED (wajib token) ──────────────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [ApiController::class, 'logout']);
    Route::get('/me',      [ApiController::class, 'me']);

    // Dashboard stats
    Route::get('/dashboard', [ApiController::class, 'dashboard']);

    // Lapangan CRUD (admin only)
    Route::post('/lapangan',              [ApiController::class, 'lapanganStore']);
    Route::post('/lapangan/{lapangan}',   [ApiController::class, 'lapanganUpdate']); // pakai POST + _method=PUT untuk multipart
    Route::put('/lapangan/{lapangan}',    [ApiController::class, 'lapanganUpdate']);
    Route::delete('/lapangan/{lapangan}', [ApiController::class, 'lapanganDestroy']);

    // Reservasi
    Route::get('/reservasi',                         [ApiController::class, 'reservasiIndex']);
    Route::get('/reservasi/{reservasi}',             [ApiController::class, 'reservasiShow']);
    Route::post('/reservasi/{reservasi}/approve',    [ApiController::class, 'reservasiApprove']);
    Route::post('/reservasi/{reservasi}/reject',     [ApiController::class, 'reservasiReject']);
    Route::delete('/reservasi/{reservasi}',          [ApiController::class, 'reservasiDestroy']);
});
