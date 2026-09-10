<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::middleware('admin')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
    });

    Route::get('/settings', function () {
        return view('settings');
    })->name('settings');

    // Alias untuk kompatibilitas
    Route::get('/setting', function () {
        return view('settings');
    })->name('settings.index');

    Route::get('/profile', function () {
        return view('settings');
    })->name('profile.edit');
});
