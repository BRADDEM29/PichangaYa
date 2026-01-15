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
use App\Http\Controllers\VerificationController; 
use App\Http\Controllers\ArenaController; // 🟢 NUEVO: Controlador de Arena
use App\Http\Controllers\LobbyController;
use App\Livewire\Arena\LobbyRoom; 

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
    
    // 🟢 BASE DE SEGURIDAD (Compartida para Carrusel y Listado)
    // Solo canchas activas de dueños no bloqueados
    $baseQuery = Cancha::with(['media', 'district', 'sports'])
        ->where('is_active', true)
        ->whereHas('user', function ($q) {
            $q->where('is_blocked', false);
        });

    // 1. OBTENER CANCHAS PARA EL CARRUSEL (Destacadas)
    // Clonamos la base para no mezclar con los filtros de búsqueda
    $featuredCanchas = (clone $baseQuery)
        ->where('is_featured', true)
        ->latest()
        ->take(6) 
        ->get();

    // 2. OBTENER CANCHAS PARA EL LISTADO (Con filtros aplicados)
    $query = clone $baseQuery;

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

    // 💡 AGREGADO: 'featuredCanchas' se envía a la vista
    return view('welcome', compact('canchas', 'districts', 'sports', 'featuredCanchas'));
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
    
    // 🟢 ELIMINAR DASHBOARD
    Route::redirect('/dashboard', '/')->name('dashboard');
    
    // Ver Canchas
    Route::resource('canchas', CanchaController::class)->only(['index', 'show']);
    
    // 🟢 NUEVA RUTA: FAVORITOS
    Route::post('/canchas/{cancha}/favorite', [CanchaController::class, 'toggleFavorite'])->name('canchas.favorite');
    
    // 📱 VERIFICACIÓN DE CELULAR (NUEVAS RUTAS)
    Route::post('/verificar-celular/enviar', [VerificationController::class, 'sendCode'])->name('verification.send');
    Route::post('/verificar-celular/confirmar', [VerificationController::class, 'verifyCode'])->name('verification.check');

    // Gestión de Reservas
    Route::post('/canchas/{cancha}/reservar', [ReservaController::class, 'store'])->name('reservas.user.store');
    Route::get('/mis-reservas', [ReservaController::class, 'userReservasIndex'])->name('reservas.user.index');
    Route::get('/reservas/{reserva}/editar', [ReservaController::class, 'editUser'])->name('reservas.edit');
    Route::put('/reservas/{reserva}/cancelar', [ReservaController::class, 'cancelUser'])->name('reservas.cancel');
    
    // Notificaciones
    Route::get('/notificaciones', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notificaciones/{id}/leer', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::get('/notifications/check-new', [NotificationController::class, 'checkNew'])->name('notifications.checkNew');
    
    // Marcar todas como leídas
    Route::post('/notificaciones/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');

    // 🟢 NUEVA RUTA AJAX (Insertada aquí)
    Route::post('/notifications/{id}/mark-read', [NotificationController::class, 'markAsReadAjax'])->name('notifications.markread.ajax');

    // 🟢 NUEVA RUTA: Chequeo de estado individual (Para la tarjeta flotante)
    Route::get('/reservas/{reserva}/check-status', [\App\Http\Controllers\ReservaController::class, 'checkStatus'])
        ->name('reservas.checkStatus');
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
        
        // 🟢 RUTA PARA ACTUALIZAR STRIKES
        Route::put('users/{user}/strikes', [AdminUserController::class, 'updateStrikes'])
            ->name('users.update_strikes');
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
            
            // Ver las canchas de un dueño
            Route::get('/owners/{user}/canchas', 'courts')->name('owners.courts'); 
            
            // Rutas para Crear Canchas desde Admin
            Route::get('/owners/{user}/canchas/create', 'createCancha')->name('owners.canchas.create');
            Route::post('/owners/{user}/canchas', 'storeCancha')->name('owners.canchas.store');
            
            // Rutas para Gestionar Reservas de Cancha
            Route::get('/canchas/{cancha}/reservas', 'canchaReservas')->name('canchas.reservas.index');

            // 🟢 NUEVA RUTA: Recarga de tabla de reservas (Para la vista admin)
            Route::get('/canchas/{cancha}/reservas-polling', 'canchaReservasPolling')->name('canchas.reservas.polling');

            // Rutas para Editar/Eliminar/Destacar
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

/*
|--------------------------------------------------------------------------
| 5. ZONA ARENA (CAMPEONATOS & MATCHMAKING)
|--------------------------------------------------------------------------
*/
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Vista Principal (Buscador + Torneos)
    Route::get('/campeonatos', [ArenaController::class, 'index'])->name('arena.index');
    
    // 🟢 RUTA DE LA SALA DE ESPERA (LOBBY)
    // El '{lobby}' indica que la URL será algo como /lobby/1, /lobby/5, etc.
    Route::get('/lobby/{lobby}', [LobbyController::class, 'show'])->name('lobby.show');
    Route::get('/lobby/{lobby}', LobbyRoom::class)->name('lobby.show');
});

/*
|--------------------------------------------------------------------------
| 6. RUTA DEL MAPA GENERAL
|--------------------------------------------------------------------------
*/
Route::get('/mapa-general', [CanchaController::class, 'mapaGeneral'])->name('mapa.index');

Route::get('/test-gd', function () {
    return extension_loaded('gd') ? "✅ Librería GD ACTIVADA" : "❌ Librería GD APAGADA";
});

Route::get('/user/profile-edit-redirect', function () {
    return redirect()->route('profile.show');
})->name('profile.edit');