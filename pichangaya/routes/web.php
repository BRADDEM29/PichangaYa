<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request; 

// 1. IMPORTACIONES DE CONTROLADORES Y MODELOS
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminDistrictController;
use App\Http\Controllers\AdminSportController;
use App\Http\Controllers\AdminServiceController;
use App\Http\Controllers\AdminOwnerController;
use App\Http\Controllers\CanchaController; 
use App\Http\Controllers\DashboardController; 
use App\Http\Controllers\ReservaController; 
use App\Models\Cancha;   
use App\Models\District; 
use App\Models\Sport;    

/*
|--------------------------------------------------------------------------
| 1. PÁGINA DE INICIO (Pública / Landing Page)
|--------------------------------------------------------------------------
*/
Route::get('/', function (Request $request) {
    // 1. Preparar consulta base
    $query = Cancha::with(['media', 'district', 'sports']);

    // 2. Filtros de búsqueda
    if ($request->filled('search')) {
        $search = $request->input('search');
        $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('address', 'like', "%{$search}%");
        });
    }
    if ($request->filled('district_id')) {
        $query->where('district_id', $request->input('district_id'));
    }
    if ($request->filled('sport_id')) {
        $query->whereHas('sports', function($q) use ($request) {
            $q->where('sports.id', $request->input('sport_id'));
        });
    }

    // 3. Obtener resultados generales (Lista de abajo)
    if ($request->anyFilled(['search', 'district_id', 'sport_id'])) {
        $canchas = $query->get();
    } else {
        $canchas = $query->latest()->take(6)->get();
    }

    // 🟢 4. LOGICA DEL CARRUSEL (Corregida)
    // Buscamos las marcadas como destacadas (is_featured = 1)
    $featuredCanchas = Cancha::where('is_featured', true)
                             ->with(['district', 'media'])
                             ->latest()
                             ->take(5)
                             ->get();

    

    // 5. Cache de selectores
    $districts = Cache::remember('all_districts', 3600, fn() => District::all());
    $sports = Cache::remember('all_sports', 3600, fn() => Sport::all());

    return view('welcome', [
        'canchas' => $canchas, 
        'featuredCanchas' => $featuredCanchas, // 🟢 ESTE ES EL NOMBRE CLAVE
        'districts' => $districts,
        'sports' => $sports
    ]);
})->name('home');

// Detalle de Cancha Pública
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
            (Features::canManageTwoFactorAuthentication() && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'))
                ? ['password.confirm'] 
                : []
        )
        ->name('two-factor.show');
});

/*
|--------------------------------------------------------------------------
| 3. DASHBOARD GENERAL (Usuarios Clientes)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/reservas/crear/{cancha}', [ReservaController::class, 'create'])->name('reservas.create');
    Route::post('/reservas', [ReservaController::class, 'store'])->name('reservas.store');
    Route::get('/reservas/mis-reservas', [ReservaController::class, 'userReservasIndex'])->name('reservas.user.index');
    Route::put('/reservas/{reserva}/cancel', [ReservaController::class, 'cancelUser'])->name('reservas.cancel');
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
        Route::get('/', function () { return view('admin.dashboard'); })->name('dashboard');

        Route::controller(AdminUserController::class)->group(function () {
            Route::get('/users', 'index')->name('users.index');
            Route::put('/users/{id}', 'update')->name('users.update');
            Route::delete('/users/{id}', 'destroy')->name('users.destroy');
        });

        Route::resource('districts', AdminDistrictController::class)->except(['create', 'edit', 'show']);
        Route::resource('sports', AdminSportController::class)->except(['create', 'edit', 'show']);
        Route::resource('services', AdminServiceController::class)->except(['create', 'edit', 'show']);

        // Gestión de Dueños y Canchas
        Route::controller(AdminOwnerController::class)->group(function () {
            Route::get('/owners', 'index')->name('owners.index');
            Route::get('/owners/{user}/courts', 'courts')->name('owners.courts');
            Route::put('/canchas/{cancha}/toggle-featured', 'toggleFeatured')->name('canchas.toggleFeatured');
            Route::get('/canchas/{cancha}/edit', 'editCancha')->name('canchas.edit');
            Route::put('/canchas/{cancha}', 'updateCancha')->name('canchas.update');
            Route::delete('/canchas/{cancha}', 'destroy')->name('canchas.destroy');
            Route::get('/owners/{user}/canchas/create', 'createCancha')->name('owners.canchas.create');
            Route::post('/owners/{user}/canchas', 'storeCancha')->name('owners.canchas.store');
        });
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
        
        Route::get('/canchas/{cancha}/historial', [CanchaController::class, 'history'])->name('canchas.history');
        Route::resource('canchas', CanchaController::class);
        Route::get('/reservas', [ReservaController::class, 'ownerReservasIndex'])->name('reservas.index');
        Route::put('/reservas/{reserva}/update-status', [ReservaController::class, 'updateStatus'])->name('reservas.updateStatus');
    });

// Pruebas Técnicas
Route::get('/test-gd', function () {
    return extension_loaded('gd') ? "✅ GD ACTIVADO" : "❌ GD APAGADO";
});

Route::get('/test-webp', function () {
    return gd_info()['WebP Support'] ? "✅ WebP OK" : "❌ WebP OFF";
});