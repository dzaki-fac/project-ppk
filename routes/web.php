<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectMemberController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:10,1');
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

    // Project & Team Management (Programmer 2)
    // Menggunakan middleware auth yang sudah tersedia.
    // Akses detail dibatasi via ProjectPolicy: view untuk owner/member,
    // update/delete/addMember/removeMember hanya untuk owner.
    Route::resource('projects', ProjectController::class);

    Route::get('projects/{project}/members', [ProjectMemberController::class, 'index'])
        ->name('projects.members.index');
    Route::post('projects/{project}/members', [ProjectMemberController::class, 'store'])
        ->name('projects.members.store');
    Route::delete('projects/{project}/members/{user}', [ProjectMemberController::class, 'destroy'])
        ->name('projects.members.destroy');

    // Tasks = isi dari project (Programmer 3)
    // Nested (project dikunci): list & buat task di dalam 1 project.
    // Akses dibatasi via ProjectPolicy: hanya owner/member project yang bisa view.
    Route::prefix('projects/{project}')->name('projects.')->group(function () {
        Route::get('tasks', [TaskController::class, 'index'])->name('tasks.index');
        Route::get('tasks/create', [TaskController::class, 'create'])->name('tasks.create');
        Route::post('tasks', [TaskController::class, 'store'])->name('tasks.store');
    });

    // Global (lintas project, selalu tampilkan badge nama project).
    Route::resource('tasks', TaskController::class)->except(['show']);
    Route::patch('/tasks/{task}/toggle', [TaskController::class, 'toggle'])->name('tasks.toggle');
});
