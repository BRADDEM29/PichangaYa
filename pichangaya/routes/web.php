<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminDistrictController;
use App\Http\Controllers\AdminSportController;

Route::middleware(['auth', 'role:admin'])->group(function () {
    
    // Dashboard Principal del Admin
    Route::get('/panel-admin', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // --- NUEVAS RUTAS DE GESTIÓN DE USUARIOS ---
    // Ver lista
    Route::get('/panel-admin/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    // Cambiar rol (Usamos PUT para actualizar)
    Route::put('/panel-admin/users/{id}', [AdminUserController::class, 'update'])->name('admin.users.update');
    // NUEVA RUTA: ELIMINAR
    Route::delete('/panel-admin/users/{id}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');

    // --- RUTAS DE DISTRITOS (CRUD) ---
    Route::get('/panel-admin/districts', [AdminDistrictController::class, 'index'])->name('admin.districts.index');
    Route::post('/panel-admin/districts', [AdminDistrictController::class, 'store'])->name('admin.districts.store');
    Route::put('/panel-admin/districts/{id}', [AdminDistrictController::class, 'update'])->name('admin.districts.update');
    Route::delete('/panel-admin/districts/{id}', [AdminDistrictController::class, 'destroy'])->name('admin.districts.destroy');

    // --- RUTAS DE DEPORTES (CRUD) ---
    Route::get('/panel-admin/sports', [AdminSportController::class, 'index'])->name('admin.sports.index');
    Route::post('/panel-admin/sports', [AdminSportController::class, 'store'])->name('admin.sports.store');
    Route::put('/panel-admin/sports/{id}', [AdminSportController::class, 'update'])->name('admin.sports.update');
    Route::delete('/panel-admin/sports/{id}', [AdminSportController::class, 'destroy'])->name('admin.sports.destroy');
});

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