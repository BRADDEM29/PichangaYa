<?php
// C:\laragon\www\PichangaYa\pichangaya\routes\web.php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth;

// Controladores
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminDistrictController;
use App\Http\Controllers\AdminSportController;
use App\Http\Controllers\AdminServiceController;
use App\Http\Controllers\AdminOwnerController;
use App\Http\Controllers\AdminDashboardController; 
use App\Http\Controllers\AdminContactController; 
use App\Http\Controllers\SuggestionController;    
use App\Http\Controllers\CanchaController; 
use App\Http\Controllers\DashboardController; 
use App\Http\Controllers\ReservaController; 
use App\Http\Controllers\NotificationController;

// Modelos
use App\Models\Cancha;   
use App\Models\District; 
use App\Models\Sport;    

/*
|--------------------------------------------------------------------------
| 1. PÁGINA DE INICIO (PÚBLICA)
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

// RUTAS PÚBLICAS
Route::get('/contacto', function () { return view('pages.contact'); })->name('contact.index');
Route::view('/nosotros', 'pages.about')->name('about');
Route::view('/faq', 'pages.faq')->name('faq');
Route::view('/registrar-mi-cancha', 'pages.register-pitch')->name('register-pitch');
Route::view('/sugerencias', 'pages.suggestions')->name('suggestions.index');
Route::get('/terminos-y-condiciones', function () { return view('terms'); })->name('terms.show');
Route::get('/politica-de-privacidad', function () { return view('policy'); })->name('policy.show');


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
    
    // Dashboard Usuario
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Ver Canchas
    Route::resource('canchas', CanchaController::class)->only(['index', 'show']);
    
    // Gestión de Reservas
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
        
        // Reportes
        Route::controller(AdminDashboardController::class)
            ->prefix('reportes')->name('reports.')   
            ->group(function () {
                Route::get('/ingresos', 'reportsIngresos')->name('ingresos');
                Route::get('/adelantados', 'reportsAdelantados')->name('adelantados');
                Route::get('/pendientes', 'reportsPendientes')->name('pendientes');
                Route::get('/cancelados', 'reportsCancelados')->name('cancelados');
                Route::get('/reservas', 'reportsReservas')->name('reservas');
                Route::get('/usuarios', 'reportsUsuarios')->name('usuarios');
                Route::get('/canchas', 'reportsCanchas')->name('canchas');
            });

        // Gestión de Usuarios
        Route::resource('users', AdminUserController::class);
        Route::patch('/users/{id}/toggle-block', [AdminUserController::class, 'toggleBlock'])->name('users.toggleBlock');

        // Recursos Admin
        Route::resource('districts', AdminDistrictController::class);
        Route::resource('sports', AdminSportController::class);
        Route::resource('services', AdminServiceController::class);
        
        // Gestión de Dueños (Listado general)
        Route::resource('owners', AdminOwnerController::class);

        // Consultas y Sugerencias
        Route::get('/consultas', [AdminContactController::class, 'index'])->name('contacts.index');
        Route::put('/consultas/{id}/status', [AdminContactController::class, 'updateStatus'])->name('contacts.updateStatus'); 
        Route::delete('/consultas/{id}', [AdminContactController::class, 'destroy'])->name('contacts.destroy');
        
        Route::get('/sugerencias-recibidas', [SuggestionController::class, 'index'])->name('suggestions.received');
        Route::put('/sugerencias-recibidas/{id}/status', [SuggestionController::class, 'updateStatus'])->name('suggestions.updateStatus'); 
        Route::delete('/sugerencias-recibidas/{id}', [SuggestionController::class, 'destroy'])->name('suggestions.destroy');
        
        // 🟢 GESTIÓN AVANZADA DE DUEÑOS Y CANCHAS
        Route::controller(AdminOwnerController::class)->group(function () {
            
            // 👇 1. ESTA ES LA RUTA QUE TE FALTABA PARA VER LAS CANCHAS
            // Se llamará: admin.owners.courts (por el prefix y name del grupo padre)
            Route::get('/owners/{user}/canchas', 'courts')->name('owners.courts'); 
            
            // Rutas para Crear Canchas desde Admin
            Route::get('/owners/{user}/canchas/create', 'createCancha')->name('owners.canchas.create');
            Route::post('/owners/{user}/canchas', 'storeCancha')->name('owners.canchas.store');
            
            // Rutas para Gestionar Reservas de Cancha
            Route::get('/canchas/{cancha}/reservas', 'canchaReservas')->name('canchas.reservas.index');

            // 👇 2. RUTAS NECESARIAS PARA LOS BOTONES DE TU VISTA (Editar, Eliminar, Destacar)
            Route::get('/canchas/{cancha}/edit', 'editCancha')->name('canchas.edit');
            Route::put('/canchas/{cancha}', 'updateCancha')->name('canchas.update');
            Route::delete('/canchas/{cancha}', 'destroy')->name('canchas.destroy');
            Route::put('/canchas/{cancha}/toggle-featured', 'toggleFeatured')->name('canchas.toggleFeatured');
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

Route::get('/test-gd', function () {
    return extension_loaded('gd') ? "✅ Librería GD ACTIVADA" : "❌ Librería GD APAGADA";
});