<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectMemberController;
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

// Project & Team Management (Programmer 2)
// Menggunakan middleware auth yang sudah tersedia.
// Akses detail dibatasi via ProjectPolicy: view untuk owner/member,
// update/delete/addMember/removeMember hanya untuk owner.
Route::middleware('auth')->group(function () {
    Route::resource('projects', ProjectController::class);

    Route::get('projects/{project}/members', [ProjectMemberController::class, 'index'])
        ->name('projects.members.index');
    Route::post('projects/{project}/members', [ProjectMemberController::class, 'store'])
        ->name('projects.members.store');
    Route::delete('projects/{project}/members/{user}', [ProjectMemberController::class, 'destroy'])
        ->name('projects.members.destroy');
});
