<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;
// Asumo que si estás usando Volt/Jetstream, el helper 'when' se puede importar o ya está disponible.
// Si hay un error, puedes necesitar importarlo desde Livewire\Volt\When, o simplemente quitarlo si no lo usas.

// 1. IMPORTACIONES DE CONTROLADORES
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminDistrictController;
use App\Http\Controllers\AdminSportController;
use App\Http\Controllers\CanchaController; // Controlador del Dueño
use App\Http\Controllers\DashboardController; // Controlador del Dashboard
use App\Http\Controllers\ReservaController; // Controlador de Reservas

/*
|--------------------------------------------------------------------------
| 1. PÁGINA DE INICIO (Pública)
|--------------------------------------------------------------------------
*/
// CAMBIO IMPORTANTE: La ruta raíz ahora usa el DashboardController para cargar las canchas
Route::get('/', [DashboardController::class, 'welcome'])->name('home');

// Detalle de Cancha Pública (Para que los invitados puedan ver el detalle sin login)
Route::get('/canchas/{cancha}', [DashboardController::class, 'show'])->name('canchas.show');

/*
|--------------------------------------------------------------------------
| 2. RUTAS DE PERFIL Y AJUSTES (Jetstream / Volt)
|--------------------------------------------------------------------------
| Protegidas por el middleware 'auth'.
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
| 3. DASHBOARD GENERAL (Usuarios Clientes)
|--------------------------------------------------------------------------
| Rutas accesibles para cualquier usuario autenticado y verificado.
*/
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- RUTAS DE RESERVAS DEL CLIENTE ---
    
    // Crear reserva
    Route::post('/reservas', [ReservaController::class, 'store'])->name('reservas.store');

    // Ver mis reservas (Esta es la ruta que te estaba dando error)
    Route::get('/reservas/mis-reservas', [ReservaController::class, 'userReservasIndex'])->name('reservas.user.index');
    
    // Las rutas de canchas.show ya no son necesarias aquí porque están definidas en la sección 1, 
    // pero si las dejas no causan conflicto. La elimino por limpieza.
    // Route::get('/canchas/{cancha}', [DashboardController::class, 'show'])->name('canchas.show');

    // 🟢 NUEVO: Cancelar mi reserva
    Route::put('/reservas/{reserva}/cancel', [ReservaController::class, 'cancelUser'])->name('reservas.cancel');

    // 🟢 NUEVO: Editar mi reserva (Muestra el formulario)
    Route::get('/reservas/{reserva}/edit', [ReservaController::class, 'editUser'])->name('reservas.edit');

});

/*
|--------------------------------------------------------------------------
| 4. ZONA ADMINISTRADOR (Prefijo: /panel-admin)
|--------------------------------------------------------------------------
| Protegido por el middleware 'role:admin'.
*/
Route::middleware(['auth', 'role:admin']) 
    ->prefix('panel-admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        // Gestión de Usuarios
        Route::controller(AdminUserController::class)->group(function () {
            Route::get('/users', 'index')->name('users.index');
            Route::put('/users/{id}', 'update')->name('users.update');
            Route::delete('/users/{id}', 'destroy')->name('users.destroy');
        });

        // Gestión de Distritos y Deportes (CRUD sin create, edit, show)
        Route::resource('districts', AdminDistrictController::class)->except(['create', 'edit', 'show']);
        Route::resource('sports', AdminSportController::class)->except(['create', 'edit', 'show']);
    });
    
/*
|--------------------------------------------------------------------------
| 5. ZONA DUEÑO (Prefijo: /panel-dueno)
|--------------------------------------------------------------------------
| Protegido por el middleware 'role:owner'.
*/
Route::middleware(['auth', 'role:owner'])
    ->prefix('panel-dueno')
    ->name('owner.')
    ->group(function () {

        // 1. Redirección Principal
        Route::redirect('/', '/panel-dueno/canchas')->name('dashboard');

        // 2. Rutas de Canchas (CRUD)
        Route::resource('canchas', CanchaController::class);
        
        // RUTAS DE GESTIÓN DE RESERVAS DEL DUEÑO
        // GET: Para que el dueño pueda ver las reservas de SUS canchas.
        Route::get('/reservas', [ReservaController::class, 'ownerReservasIndex'])->name('reservas.index');
        // PUT: Para actualizar el estado de una reserva (ej: confirmar, cancelar)
        Route::put('/reservas/{reserva}/update-status', [ReservaController::class, 'updateStatus'])->name('reservas.updateStatus');
        
    });