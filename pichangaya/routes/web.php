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

    // Autenticación de dos factores
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
*/
Route::middleware(['auth', 'role:admin']) 
    ->prefix('panel-admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        // Gestión de Usuarios, Distritos y Deportes
        Route::controller(AdminUserController::class)->group(function () {
            Route::get('/users', 'index')->name('users.index');
            Route::put('/users/{id}', 'update')->name('users.update');
            Route::delete('/users/{id}', 'destroy')->name('users.destroy');
        });

        Route::resource('districts', AdminDistrictController::class)->except(['create', 'edit', 'show']);
        Route::resource('sports', AdminSportController::class)->except(['create', 'edit', 'show']);
    });
/*
|--------------------------------------------------------------------------
| 5. ZONA DUEÑO (Prefijo: /panel-dueno)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:owner'])
    ->prefix('panel-dueno')
    ->name('owner.')
    ->group(function () {

        // 1. CAMBIO PRINCIPAL: Redireccionar la raíz '/' a 'canchas'
        // Cuando entren a "127.0.0.1:8000/panel-dueno", Laravel los enviará a ".../canchas"
        // En routes/web.php dentro del grupo 'panel-dueno'
Route::redirect('/', '/panel-dueno/canchas')->name('dashboard');

        // 2. TUS RUTAS DE CANCHAS (Asegúrate de haber quitado ->names() como vimos antes)
        Route::resource('canchas', CanchaController::class);
        
    });