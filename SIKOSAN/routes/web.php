<?php

use App\Http\Controllers\SessionController;
use App\Http\Controllers\penghuniController;
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
    Route::get('/Dpemilik', [PemilikController::class, 'indexSK'])->name('pemilik.dashboard');
    Route::get('/Dpenghuni', [penghuniController::class, 'indexPi'])->name('penghuni.dashboard');
    
    // Kontrol Kos (Detail Kos)
    Route::get('/kontrolkos/{kos}', [PemilikController::class, 'kontrolKos'])->name('kos.kontrol');

    // Route Profil Penghuni & Join Kos
    Route::put('/profile/update', [penghuniController::class, 'update'])->name('profile.update');
    Route::get('/profil-penghuni', [penghuniController::class, 'profil'])->name('profil.penghuni');
    Route::post('/join-kos', [penghuniController::class, 'joinKos'])->name('join.kos');
    
    // CRUD Kos (Properti)
    Route::get('/kos/create', [PemilikController::class, 'create'])->name('kos.create');
    Route::post('/kos', [PemilikController::class, 'store'])->name('kos.store');
    Route::get('/kos', [PemilikController::class, 'indexSK'])->name('kos.show');
    Route::get('/kos/{kos}/edit', [PemilikController::class, 'edit'])->name('kos.edit');
    Route::put('/kos/{kos}', [PemilikController::class, 'update'])->name('kos.update');
    Route::delete('/kos/{kos}', [PemilikController::class, 'destroy'])->name('kos.destroy');

    // Routes untuk Kamar - SESUAI ALUR YANG DIINGINKAN
    Route::get('/kamar/{kos}', [PemilikController::class, 'indexKamar'])->name('kamar.index');
    
    // Route untuk form tambah kamar (dari kontrol kos)
    Route::get('/kamar/{kos}/create', [PemilikController::class, 'createKamarForm'])->name('kamar.create');
    
    // Route untuk menyimpan kamar baru
    Route::post('/kamar/{kos}/store', [PemilikController::class, 'storeKamar'])->name('kamar.store');

    // Routes lainnya untuk kamar
    Route::get('/kamar/{kamar}/show', [PemilikController::class, 'showKamar'])->name('kamar.show');
    Route::get('/kamar/{kamar}/edit', [PemilikController::class, 'editKamar'])->name('kamar.edit');
    Route::put('/kamar/{kamar}', [PemilikController::class, 'updateKamar'])->name('kamar.update');
    Route::delete('/kamar/{kamar}', [PemilikController::class, 'destroyKamar'])->name('kamar.destroy');
});