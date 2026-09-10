<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
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
