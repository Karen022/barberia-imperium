<?php
// routes/admin.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Routes for the admin / internal dashboard. These routes are grouped with
| prefix "admin" and typically protected by "auth" and a role middleware.
|
*/

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth','role:admin,barber'])
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Aquí agregarás otras rutas del panel:
        // Route::resource('services', Admin\ServiceController::class);
        // Route::resource('products', Admin\ProductController::class);
        // Route::resource('appointments', Admin\AppointmentController::class);
        // etc.
    });
