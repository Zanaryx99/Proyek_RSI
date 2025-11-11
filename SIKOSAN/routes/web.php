<?php

use App\Http\Controllers\SessionController;
use App\Http\Controllers\PenghuniController;
use App\Http\Controllers\PemilikController;
use App\Http\Controllers\KamarController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\PembayaranController;
use App\Livewire\Chat;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

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
    Route::get('/Dpenghuni', [PenghuniController::class, 'indexPi'])->name('penghuni.dashboard');

    // Kontrol Kos (Detail Kos)
    Route::get('/kontrolkos/{kos}', [PemilikController::class, 'kontrolKos'])->name('kos.kontrol');

    // Route Profil Penghuni & Join Kos
    Route::put('/profile/update', [PenghuniController::class, 'update'])->name('profile.update');
    Route::get('/profil-penghuni', [PenghuniController::class, 'profil'])->name('profil.penghuni');
    Route::post('/join-kos', [PenghuniController::class, 'joinKos'])->name('join.kos');

    // Route Profil Pemilik
    Route::get('/profil-pemilik', [PemilikController::class, 'profil'])->name('profil.pemilik');

    // CRUD Kos (Properti)
    Route::get('/kos/create', [PemilikController::class, 'create'])->name('kos.create');
    Route::post('/kos', [PemilikController::class, 'store'])->name('kos.store');
    Route::get('/kos', [PemilikController::class, 'indexSK'])->name('kos.show');
    Route::get('/kos/{kos}/edit', [PemilikController::class, 'edit'])->name('kos.edit');
    Route::put('/kos/{kos}', [PemilikController::class, 'update'])->name('kos.update');
    Route::delete('/kos/{kos}', [PemilikController::class, 'destroy'])->name('kos.destroy');

    // Routes untuk Kamar
    Route::get('/kamar/{kos}', [PemilikController::class, 'indexKamar'])->name('kamar.index');
    Route::get('/kamar/{kos}/create', [PemilikController::class, 'createKamarForm'])->name('kamar.create');
    Route::post('/kamar/{kos}/store', [PemilikController::class, 'storeKamar'])->name('kamar.store');
    Route::get('/kamar/{kamar}/show', [PemilikController::class, 'showKamar'])->name('kamar.show');
    Route::get('/kamar/{kamar}/edit', [PemilikController::class, 'editKamar'])->name('kamar.edit');
    Route::put('/kamar/{kamar}', [PemilikController::class, 'updateKamar'])->name('kamar.update');
    Route::delete('/kamar/{kamar}', [PemilikController::class, 'destroyKamar'])->name('kamar.destroy');
    
    // Route untuk update status kamar
    Route::patch('/kamar/{kamar}/status', [PemilikController::class, 'updateStatusKamar'])->name('kamar.update-status');

    // Routes untuk pendaftaran kamar oleh penghuni
    Route::post('/kamar/daftar', [PenghuniController::class, 'daftarKamar'])->name('kamar.daftar');
    Route::post('/kamar/{id}/keluar', [PenghuniController::class, 'keluarKamar'])->name('kamar.keluar');

    // Routes untuk review
    Route::middleware(['auth'])->group(function () {
        Route::post('/review', [ReviewController::class, 'store'])->name('review.store');
        Route::put('/review', [ReviewController::class, 'update'])->name('review.update');
        Route::get('/kos/{kosId}/reviews', [ReviewController::class, 'getKosReviews'])->name('review.getKosReviews');
        Route::delete('/review', [ReviewController::class, 'destroy'])->name('review.destroy');
});

    Route::post('/pembayaran', [PembayaranController::class, 'store'])->name('pembayaran.store');
    Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran.index');
  
    Route::get('/chat/{user}',Chat::class) ->name('chat');
    
});

