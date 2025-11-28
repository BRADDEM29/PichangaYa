<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\CanchaController; // <--- ¡Importante importar esto!

/*
|--------------------------------------------------------------------------
| 1. RUTA PÚBLICA (Home)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
})->name('home');

/*
|--------------------------------------------------------------------------
| 2. RUTAS DE CONFIGURACIÓN DE PERFIL (Jetstream / Volt)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});

/*
|--------------------------------------------------------------------------
| 3. DASHBOARD GENERAL (Usuarios Normales)
|--------------------------------------------------------------------------
*/
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

/*
|--------------------------------------------------------------------------
| 4. ZONA DEL ADMINISTRADOR (Role: admin)
|--------------------------------------------------------------------------
| URL Base: /panel-admin
| Nombre Ruta Base: admin.
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('panel-admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard Principal
        Route::get('/', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        // Gestión de Usuarios (Controller Agrupado)
        Route::controller(AdminUserController::class)->group(function () {
            Route::get('/users', 'index')->name('users.index');
            Route::put('/users/{id}', 'update')->name('users.update');
            Route::delete('/users/{id}', 'destroy')->name('users.destroy');
        });

        // FUTURO: Aquí agregarás las rutas de Deportes y Distritos en el Sprint 3
        // Route::resource('sports', SportController::class);
    });

/*
|--------------------------------------------------------------------------
| 5. ZONA DEL DUEÑO (Role: owner)
|--------------------------------------------------------------------------
| URL Base: /panel-dueno
| Nombre Ruta Base: owner.
*/
Route::middleware(['auth', 'role:owner'])
    ->prefix('panel-dueno')
    ->name('owner.')
    ->group(function () {

        // Dashboard del Dueño
        Route::get('/', function () {
            return view('owner.dashboard');
        })->name('dashboard');

        // Gestión de Canchas (CRUD Completo)
        // Esto crea automáticamente: index, create, store, show, edit, update, destroy
        Route::resource('canchas', CanchaController::class);
    });