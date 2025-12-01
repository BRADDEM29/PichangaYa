<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

// 1. IMPORTACIONES DE CONTROLADORES
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminDistrictController;
use App\Http\Controllers\AdminSportController;
use App\Http\Controllers\CanchaController; // Controlador del Dueño
use App\Http\Controllers\DashboardController;//controlador del dashboard
/*
|--------------------------------------------------------------------------
| 1. PÁGINA DE INICIO (Pública)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
})->name('home');

/*
|--------------------------------------------------------------------------
| 2. RUTAS DE PERFIL Y AJUSTES (Jetstream / Volt)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    // Autenticación de dos factores (si está habilitada)
    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication() && 
                Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                []
            )
        )
        ->name('two-factor.show');
});

/*
|--------------------------------------------------------------------------
| 3. DASHBOARD GENERAL (Usuarios Clientes / Redirección)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum',config('jetstream.auth_session'),'verified'])->group(function () {
    
    // Ahora el dashboard usa tu lógica de búsqueda
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

});

/*
|--------------------------------------------------------------------------
| 4. ZONA ADMINISTRADOR (Prefijo: /panel-admin)
|--------------------------------------------------------------------------
| Aquí gestionas usuarios, distritos y deportes maestros.
*/
Route::middleware(['auth', 'role:admin']) // Asegúrate de tener el Middleware 'role' registrado
    ->prefix('panel-admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard del Admin
        Route::get('/', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        // Gestión de Usuarios
        Route::controller(AdminUserController::class)->group(function () {
            Route::get('/users', 'index')->name('users.index');
            Route::put('/users/{id}', 'update')->name('users.update');
            Route::delete('/users/{id}', 'destroy')->name('users.destroy');
        });

        // Gestión de Distritos
        Route::controller(AdminDistrictController::class)->group(function () {
            Route::get('/districts', 'index')->name('districts.index');
            Route::post('/districts', 'store')->name('districts.store');
            Route::put('/districts/{id}', 'update')->name('districts.update');
            Route::delete('/districts/{id}', 'destroy')->name('districts.destroy');
        });

        // Gestión de Deportes
        Route::controller(AdminSportController::class)->group(function () {
            Route::get('/sports', 'index')->name('sports.index');
            Route::post('/sports', 'store')->name('sports.store');
            Route::put('/sports/{id}', 'update')->name('sports.update');
            Route::delete('/sports/{id}', 'destroy')->name('sports.destroy');
        });
    });

/*
|--------------------------------------------------------------------------
| 5. ZONA DUEÑO (Prefijo: /panel-dueno)
|--------------------------------------------------------------------------
| Aquí el dueño gestiona sus canchas y ve sus reservas.
*/
Route::middleware(['auth', 'role:owner'])
    ->prefix('panel-dueno')
    ->name('owner.') // Esto añade 'owner.' a todas las rutas de adentro
    ->group(function () {

        // A. Dashboard Principal del Dueño
        // Ruta: /panel-dueno/
        // Nombre: owner.dashboard
        // Archivo: resources/views/owner/dashboard.blade.php
        Route::get('/', function () {
            return view('owner.dashboard');
        })->name('dashboard');

        // B. CRUD de Canchas
        // Ruta: /panel-dueno/canchas
        // Nombres generados: owner.canchas.index, owner.canchas.create, etc.
        Route::resource('canchas', CanchaController::class);
        
    });