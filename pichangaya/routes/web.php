<?php
// C:\laragon\www\PichangaYa\pichangaya\routes\web.php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth;

// 1. IMPORTACIONES DE CONTROLADORES
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminDistrictController;
use App\Http\Controllers\AdminSportController;
use App\Http\Controllers\AdminServiceController;
use App\Http\Controllers\AdminOwnerController;
use App\Http\Controllers\AdminDashboardController; 

// 🟢 CORREGIDO: Importamos desde la raíz de Controllers (ya no desde Admin\...)
use App\Http\Controllers\AdminContactController; 
use App\Http\Controllers\SuggestionController;   

use App\Http\Controllers\CanchaController; 
use App\Http\Controllers\DashboardController; 
use App\Http\Controllers\ReservaController; 
use App\Http\Controllers\NotificationController;

use App\Models\Cancha;   
use App\Models\District; 
use App\Models\Sport;    

/*
|--------------------------------------------------------------------------
| 1. PÁGINA DE INICIO
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
| 2. RUTAS DE USUARIO AUTENTICADO
|--------------------------------------------------------------------------
*/
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Ver Canchas
    Route::resource('canchas', CanchaController::class)->only(['index', 'show']);
    
    // 🟢 GESTIÓN DE RESERVAS DEL USUARIO
    Route::post('/canchas/{cancha}/reservar', [ReservaController::class, 'store'])->name('reservas.user.store');
    Route::get('/mis-reservas', [ReservaController::class, 'userReservasIndex'])->name('reservas.user.index');
    
    Route::get('/reservas/{reserva}/editar', [ReservaController::class, 'editUser'])->name('reservas.edit');
    Route::put('/reservas/{reserva}/cancelar', [ReservaController::class, 'cancelUser'])->name('reservas.cancel');
    
    // Notificaciones
    Route::get('/notificaciones', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notificaciones/{id}/leer', [NotificationController::class, 'markAsRead'])->name('notifications.read');
});

/*
|--------------------------------------------------------------------------
| 3. ZONA ADMINISTRADOR
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        Route::resource('users', AdminUserController::class);
        Route::resource('districts', AdminDistrictController::class);
        Route::resource('sports', AdminSportController::class);
        Route::resource('services', AdminServiceController::class);
        Route::resource('owners', AdminOwnerController::class);

        // 🟢 RUTAS DE CONTACTO (Consultas)
        Route::get('/consultas', [AdminContactController::class, 'index'])->name('contacts.index');
        Route::delete('/consultas/{id}', [AdminContactController::class, 'destroy'])->name('contacts.destroy');
        
        // 🟢 RUTAS DE SUGERENCIAS
        Route::get('/sugerencias-recibidas', [SuggestionController::class, 'index'])->name('suggestions.received');
        Route::delete('/sugerencias-recibidas/{id}', [SuggestionController::class, 'destroy'])->name('suggestions.destroy');
        
        // Gestión Avanzada
        Route::controller(AdminOwnerController::class)->group(function () {
            Route::get('/owners/{owner}/canchas/create', 'createCancha')->name('owners.canchas.create');
            Route::post('/owners/{owner}/canchas', 'storeCancha')->name('owners.canchas.store');
            Route::get('/canchas/{cancha}/reservas', 'canchaReservas')->name('canchas.reservas.index');
        });

        Route::put('/reservas/{reserva}/status', [ReservaController::class, 'updateStatus'])->name('reservas.updateStatus');
    });

/*
|--------------------------------------------------------------------------
| 4. ZONA DUEÑO
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

/*
|--------------------------------------------------------------------------
| 5. OTROS (PÁGINAS INFORMATIVAS Y CONTACTO)
|--------------------------------------------------------------------------
*/
Route::view('/nosotros', 'pages.about')->name('about');
Route::view('/faq', 'pages.faq')->name('faq');
Route::view('/registrar-mi-cancha', 'pages.register-pitch')->name('register-pitch');

// Solo usuarios registrados pueden ver la página de contacto
Route::get('/contacto', function () {
    if (!Auth::check()) {
        return redirect()->route('register');
    }
    return view('pages.contact');
})->name('contact.index');

Route::view('/sugerencias', 'pages.suggestions')->name('suggestions.index');

Route::get('/terminos-y-condiciones', function () {
    return view('terms', ['terms' => 'Contenido...']);
})->name('terms.show');

Route::get('/politica-de-privacidad', function () {
    return view('policy', ['policy' => 'Contenido...']);
})->name('policy.show');

Route::get('/test-gd', function () {
    return extension_loaded('gd') ? "✅ Librería GD ACTIVADA" : "❌ Librería GD APAGADA";
});