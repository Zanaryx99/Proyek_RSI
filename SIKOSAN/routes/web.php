<?php

use App\Http\Controllers\SessionController;
use App\Http\Controllers\UsersController;
use App\Http\Middleware\UserAkses;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::middleware(['guest'])->group(function () {
    Route::get('/login', [SessionController::class, 'index'])->name('login');
    Route::post('/login', [SessionController::class, 'login']);
    Route::get('/register', [SessionController::class, 'register']);
    Route::post('/register/create', [SessionController::class, 'create']);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/DPenghuni', [UsersController::class, 'index'])->middleware(UserAkses::class . ':Penghuni');
    Route::get('/Dpemilik', [UsersController::class, 'index'])->middleware(UserAkses::class . ':Pemilik');
    Route::get('/logout', [SessionController::class, 'logout']);
});


Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/home');
    }
    return redirect('/login');
});
