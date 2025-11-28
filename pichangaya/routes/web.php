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

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

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

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
// Ruta protegida: Solo entra el ADMIN
Route::get('/panel-admin', function () {
    // CAMBIO: Ahora retornamos la vista 'admin.dashboard'
    return view('admin.dashboard');
})->middleware(['auth', 'role:admin'])->name('admin.dashboard');

// Ruta protegida: Solo entra el DUEÑO
Route::get('/panel-dueno', function () {
    return "<h1>¡Bienvenido Dueño de Cancha!</h1>";
})->middleware(['auth', 'role:owner']);
