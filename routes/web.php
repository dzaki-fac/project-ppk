<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('home');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// ── Projects = wadah ──────────────────────────────────
// /projects → daftar project · /projects/{project} → detail + tasks miliknya
Route::resource('projects', ProjectController::class);

// ── Tasks = isi dari project ──────────────────────────
// Nested (project dikunci): buat & list task di dalam 1 project
Route::prefix('projects/{project}')->name('projects.')->group(function () {
    Route::get('tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::get('tasks/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('tasks', [TaskController::class, 'store'])->name('tasks.store');
});

// Global (lintas project, selalu tampilkan badge nama project)
Route::resource('tasks', TaskController::class)->except(['show']);
Route::patch('/tasks/{task}/toggle', [TaskController::class, 'toggle'])->name('tasks.toggle');

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
