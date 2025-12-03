<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

// 1. IMPORTACIONES DE MODELOS
use App\Models\Cancha;
use App\Models\District;
use App\Models\Sport;

// 2. IMPORTACIONES DE CONTROLADORES
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminDistrictController;
use App\Http\Controllers\AdminSportController;
use App\Http\Controllers\CanchaController; // Controlador del Dueño
use App\Http\Controllers\DashboardController; // Controlador del Dashboard
use App\Http\Controllers\ReservaController; // Controlador de Reservas

/*
|--------------------------------------------------------------------------
| 1. PÁGINA DE INICIO (Pública / Welcome)
|--------------------------------------------------------------------------
*/
Route::get('/', function (Request $request) {
    // Lógica de búsqueda
    $query = Cancha::query();

    // Filtro por Texto
    if ($request->filled('search')) {
        $query->where('name', 'like', '%' . $request->input('search') . '%')
              ->orWhere('address', 'like', '%' . $request->input('search') . '%');
    }

    // Filtro por Distrito
    if ($request->filled('district_id')) {
        $query->where('district_id', $request->input('district_id'));
    }

    // Filtro por Deporte
    if ($request->filled('sport_id')) {
        $query->where('sport_id', $request->input('sport_id'));
    }

    $canchas = $query->get();
    $districts = District::all();
    $sports = Sport::all();

    return view('welcome', compact('canchas', 'districts', 'sports'));
})->name('home');

// RUTA PÚBLICA: Detalle de Cancha
Route::get('/canchas/{cancha}', [DashboardController::class, 'show'])->name('canchas.show');


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

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            \Illuminate\Support\Arr::wrap(
                Features::canManageTwoFactorAuthentication() && 
                Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword')
                ? ['password.confirm']
                : []
            )
        )
        ->name('two-factor.show');
});


/*
|--------------------------------------------------------------------------
| 3. DASHBOARD GENERAL (Usuarios Clientes)
|--------------------------------------------------------------------------
| Rutas para usuarios logueados: Reservar, ver mis reservas, etc.
*/
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- RUTAS DE RESERVAS DEL CLIENTE ---
    
    // 🟢 [CORRECCIÓN] ESTA ES LA RUTA QUE FALTABA
    // Muestra el formulario para crear una reserva nueva
    Route::get('/reservas/crear/{cancha}', [ReservaController::class, 'create'])->name('reservas.create');

    // Procesar el formulario (Guardar en BD)
    Route::post('/reservas', [ReservaController::class, 'store'])->name('reservas.store');

    // Ver mis reservas
    Route::get('/reservas/mis-reservas', [ReservaController::class, 'userReservasIndex'])->name('reservas.user.index');
    
    // Cancelar mi reserva
    Route::put('/reservas/{reserva}/cancel', [ReservaController::class, 'cancelUser'])->name('reservas.cancel');

    // Editar mi reserva
    Route::get('/reservas/{reserva}/edit', [ReservaController::class, 'editUser'])->name('reservas.edit');
});


/*
|--------------------------------------------------------------------------
| 4. ZONA ADMINISTRADOR
|--------------------------------------------------------------------------
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

        // Distritos y Deportes
        Route::resource('districts', AdminDistrictController::class)->except(['create', 'edit', 'show']);
        Route::resource('sports', AdminSportController::class)->except(['create', 'edit', 'show']);
    });
    

/*
|--------------------------------------------------------------------------
| 5. ZONA DUEÑO
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:owner'])
    ->prefix('panel-dueno')
    ->name('owner.')
    ->group(function () {

        Route::redirect('/', '/panel-dueno/canchas')->name('dashboard');

        // Rutas de Canchas
        Route::resource('canchas', CanchaController::class);
        
        // Gestión de Reservas (Dueño)
        Route::get('/reservas', [ReservaController::class, 'ownerReservasIndex'])->name('reservas.index');
        Route::put('/reservas/{reserva}/update-status', [ReservaController::class, 'updateStatus'])->name('reservas.updateStatus');
        
    });

// REDIRECCIÓN INTELIGENTE
Route::get('/login-redirect', function () {
    session()->put('url.intended', url()->previous());
    return redirect()->route('login');
})->name('login.redirect');