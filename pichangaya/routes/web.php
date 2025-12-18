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
use App\Http\Controllers\AdminDashboardController; 
use App\Http\Controllers\CanchaController; 
use App\Http\Controllers\DashboardController; 
use App\Http\Controllers\ReservaController; 
use App\Http\Controllers\NotificationController;

use App\Models\Cancha;   
use App\Models\District; 
use App\Models\Sport;    

/*
|--------------------------------------------------------------------------
| 1. PÁGINA DE INICIO (Pública con Búsqueda y Filtros)
|--------------------------------------------------------------------------
*/
Route::get('/', function (Request $request) {
    $query = Cancha::with(['media', 'district', 'sports']);

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

    $canchas = $query->latest()->get();
    $districts = District::all();
    $sports = Sport::all();

    return view('welcome', compact('canchas', 'districts', 'sports'));
})->name('home');

/*
|--------------------------------------------------------------------------
| 2. RUTAS DE USUARIO AUTENTICADO (Dashboard, Reservas y Notificaciones)
|--------------------------------------------------------------------------
*/
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    
    // Dashboard Principal
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Ver Canchas
    Route::resource('canchas', CanchaController::class)->only(['index', 'show']);
    
    // Gestión de Reservas del Usuario (Nombres corregidos para el Navigation Menu)
    Route::post('/canchas/{cancha}/reservar', [ReservaController::class, 'store'])->name('reservas.user.store');
    Route::get('/mis-reservas', [ReservaController::class, 'index'])->name('reservas.user.index');
    
    // Notificaciones
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
});

/*
|--------------------------------------------------------------------------
| 3. ZONA ADMINISTRADOR (Role: admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        // Mantenimientos Base
        Route::resource('users', AdminUserController::class);
        Route::resource('districts', AdminDistrictController::class);
        Route::resource('sports', AdminSportController::class);
        Route::resource('services', AdminServiceController::class);
        Route::resource('owners', AdminOwnerController::class);

        // --- MEJORA: Nueva ruta para ver el buzón de sugerencias ---
        Route::get('/sugerencias-recibidas', [App\Http\Controllers\Admin\SuggestionController::class, 'index'])->name('suggestions.received');

        // Gestión Avanzada de Canchas y Reservas desde Admin
        Route::controller(AdminOwnerController::class)->group(function () {
            Route::get('/owners/{owner}/canchas/create', 'createCancha')->name('owners.canchas.create');
            Route::post('/owners/{owner}/canchas', 'storeCancha')->name('owners.canchas.store');
            Route::get('/canchas/{cancha}/reservas', 'canchaReservas')->name('canchas.reservas.index');
        });

        Route::put('/reservas/{reserva}/status', [ReservaController::class, 'updateStatus'])->name('reservas.updateStatus');
    });

/*
|--------------------------------------------------------------------------
| 4. ZONA DUEÑO (Role: owner)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:owner'])
    ->prefix('panel-dueno')
    ->name('owner.')
    ->group(function () {
        
        Route::redirect('/', '/panel-dueno/canchas')->name('dashboard');
        
        // Gestión de canchas propias
        Route::get('/canchas/{cancha}/historial', [CanchaController::class, 'history'])->name('canchas.history');
        Route::resource('canchas', CanchaController::class);
        
        // Gestión de Reservas recibidas por el dueño
        Route::get('/reservas', [ReservaController::class, 'ownerReservasIndex'])->name('reservas.index');
        Route::put('/reservas/{reserva}/update-status', [ReservaController::class, 'updateStatus'])->name('reservas.updateStatus');
    });

/*
|--------------------------------------------------------------------------
| 5. PÁGINAS INFORMATIVAS Y SOPORTE
|--------------------------------------------------------------------------
*/
Route::view('/nosotros', 'pages.about')->name('about');
Route::view('/faq', 'pages.faq')->name('faq');
Route::view('/registrar-mi-cancha', 'pages.register-pitch')->name('register-pitch');

// Contacto y Sugerencias (Vistas que cargan componentes Livewire)
Route::view('/contacto', 'pages.contact')->name('contact.index');
Route::view('/sugerencias', 'pages.suggestions')->name('suggestions.index');

/*
|--------------------------------------------------------------------------
| 6. SECCIÓN LEGAL
|--------------------------------------------------------------------------
*/
Route::get('/terminos-y-condiciones', function () {
    return view('terms', ['terms' => 'Contenido de los términos y condiciones...']);
})->name('terms.show');

Route::get('/politica-de-privacidad', function () {
    return view('policy', ['policy' => 'Contenido de la política de privacidad...']);
})->name('policy.show');

/*
|--------------------------------------------------------------------------
| 7. PRUEBAS TÉCNICAS
|--------------------------------------------------------------------------
*/
Route::get('/test-gd', function () {
    return extension_loaded('gd') ? "✅ Librería GD ACTIVADA" : "❌ Librería GD APAGADA";
});