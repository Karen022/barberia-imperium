<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\CashRegisterController;
use App\Http\Controllers\ClientAppointmentController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ContactController;

/*
|--------------------------------------------------------------------------
| Landing pública (sin login)
|--------------------------------------------------------------------------
*/

Route::get('/', [LandingController::class, 'index'])->name('landing.home');
Route::get('/services', [LandingController::class, 'services'])->name('landing.services');
Route::get('/contact', fn() => view('landing.contact'))->name('landing.contact');
Route::get('/booking', fn() => view('landing.booking'))->name('landing.booking'); 
Route::get('/products', [LandingController::class, 'products'])->name('landing.products');
Route::post('/contact', [ContactController::class, 'send'])->name('landing.contact.send');
/*
|--------------------------------------------------------------------------
| Dashboard interno (requiere login)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin|barber'])
    ->prefix('dashboard')
    ->name('dashboard.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Dashboard routes (authenticated users)
        |--------------------------------------------------------------------------
        */

        Route::get('/', [DashboardController::class, 'index'])->name('index');

        Route::resource('services', ServiceController::class);

        Route::resource('appointments', AppointmentController::class);
        Route::patch('appointments/{appointment}/status/{status}', [AppointmentController::class, 'updateStatus'])->name('appointments.status');

        Route::resource('sales', SaleController::class);
        
    
        /*
        |--------------------------------------------------------------------------
        | Rutas SOLO para admin
        |--------------------------------------------------------------------------
        */
        
    Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->group(function () {
        // Listado de usuarios
        Route::resource('users', UserController::class);

        Route::get('clients', [UserController::class, 'clients'])->name('clients.index');
        Route::get('barbers', [UserController::class, 'barbers'])->name('barbers.index');
        Route::get('barbers/create', [UserController::class, 'createBarber'])->name('barbers.create');
        Route::post('barbers/store', [UserController::class, 'storeBarber'])->name('barbers.store');
        Route::patch('barbers/{user}/toggleStatus', [UserController::class, 'toggleStatus'])->name('barbers.toggleStatus');

        Route::resource('cash', CashRegisterController::class);

        Route::resource('products', ProductController::class);
        Route::patch('/products/{product}/toggle-featured', [ProductController::class, 'toggleFeatured'])->name('products.toggle-featured');
    });

});

Route::middleware('auth')->group(function () {
    Route::get('/turnos', [ClientAppointmentController::class, 'create'])
        ->name('landing.appointments.create');

    Route::post('/turnos', [ClientAppointmentController::class, 'store'])
        ->name('landing.appointments.store');

    Route::get('/turnos/slots', [ClientAppointmentController::class, 'availableSlots'])
        ->name('landing.appointments.slots');

    Route::get('/mis-turnos', [ClientAppointmentController::class, 'myAppointments'])
        ->name('landing.appointments.my');

    Route::patch('/turnos/{appointment}/cancel', [ClientAppointmentController::class, 'cancel'])
        ->name('landing.appointments.cancel');
});
/*
|--------------------------------------------------------------------------
| Perfil generado por Breeze
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
