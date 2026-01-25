<?php

use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\DoctorProfileController;

use App\Http\Controllers\Admin\DashboardController;

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Admin Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // User Management
        Route::get('/users', [UserController::class, 'index'])
            ->name('users.index');

        Route::get('/users/create', [UserController::class, 'create'])
            ->name('users.create');

        Route::post('/users', [UserController::class, 'store'])
            ->name('users.store');

        Route::get('/users/{user}/edit', [UserController::class, 'edit'])
            ->name('users.edit');

        Route::put('/users/{user}', [UserController::class, 'update'])
            ->name('users.update');

        Route::patch('/users/{user}/status', [UserController::class, 'toggleStatus'])
            ->name('users.status');

            Route::get('/departments', [DepartmentController::class, 'index'])
            ->name('departments.index');

        Route::get('/departments/create', [DepartmentController::class, 'create'])
            ->name('departments.create');

        Route::post('/departments', [DepartmentController::class, 'store'])
            ->name('departments.store');

        Route::patch('/departments/{department}/status', [DepartmentController::class, 'toggleStatus'])
            ->name('departments.status');

            Route::get('/doctors', [DoctorProfileController::class, 'index'])
    ->name('doctors.index');

Route::get('/doctors/{user}/profile', [DoctorProfileController::class, 'edit'])
    ->name('doctors.profile.edit');

Route::post('/doctors/{user}/profile', [DoctorProfileController::class, 'update'])
    ->name('doctors.profile.update');
    });
