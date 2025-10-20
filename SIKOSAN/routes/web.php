<?php

use App\Http\Controllers\SessionController;
use App\Http\Controllers\UsersController;
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
    // Dashboard Penghuni
    Route::get('/DPenghuni', function () {
        if (Auth::user()->peran !== 'Penghuni') {
            return redirect('/Dpemilik');
        }
        return app(UsersController::class)->dashboardPenghuni();
    })->name('penghuni.dashboard');

    // Dashboard Pemilik
    Route::get('/Dpemilik', function () {
        if (Auth::user()->peran !== 'Pemilik') {
            return redirect('/DPenghuni');
        }
        return app(UsersController::class)->dashboardPemilik();
    })->name('pemilik.dashboard');

    // Logout
    Route::get('/logout', function () {
        Auth::logout();
        return redirect('/login')->with('success', 'Berhasil logout.');
    })->name('logout');
});

// Route root (/)
Route::get('/', function () {
    return Auth::check() ? redirect('/home') : redirect('/login');
});

// Route home (penentu arah login)
Route::get('/home', function () {
    if (!Auth::check()) {
        return redirect('/login');
    }

    return Auth::user()->peran === 'Pemilik'
        ? redirect('/Dpemilik')
        : redirect('/DPenghuni');
})->middleware('auth');
