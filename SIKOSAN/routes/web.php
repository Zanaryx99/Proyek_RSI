<?php

use App\Http\Controllers\SessionController;
use App\Http\Controllers\PemilikController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Routes untuk guest (belum login)
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [SessionController::class, 'index'])->name('login');
    Route::post('/login', [SessionController::class, 'login']);
    Route::get('/register', [SessionController::class, 'register']);
    Route::post('/register/create', [SessionController::class, 'create']);
});

// Routes untuk user yang sudah login
Route::middleware(['auth'])->group(function () {

    // Logout
    Route::get('/logout', function () {
        Auth::logout();
        return redirect('/login')->with('success', 'Berhasil logout.');
    })->name('logout');
});

// Route root (/)
Route::get('/', function () {
    return Auth::check() ? redirect('/Dpemilik') : redirect('/login');
});

Route::middleware(['auth'])->group(function () {
    // Route untuk dashboard utama
    Route::get('/Dpemilik', [PemilikController::class, 'indexGG'])->name('pemilik.dashboard');

    Route::get('/kos/create', [PemilikController::class, 'create'])->name('kos.create');
    Route::post('/kos', [PemilikController::class, 'store'])->name('kos.store');
    Route::get('/kos', [PemilikController::class, 'indexSK'])->name('kos.show');
    Route::get('/kos/{kos}/edit', [PemilikController::class, 'edit'])->name('kos.edit');
    Route::put('/kos/{kos}', [PemilikController::class, 'update'])->name('kos.update');
    Route::delete('/kos/{kos}', [PemilikController::class, 'destroy'])->name('kos.destroy');
});
