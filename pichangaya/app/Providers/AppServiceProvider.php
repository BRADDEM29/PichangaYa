<?php
//C:\laragon\www\PichangaYa\pichangaya\app\Providers\AppServiceProvider.php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Event; // 🟢 Importante para registrar el evento

// Modelos
use App\Models\Cancha;
use App\Models\Reserva;

// Policies
use App\Policies\CanchaPolicy;
use App\Policies\ReservaPolicy;

// Observador
use App\Observers\ReservaObserver;

// Eventos y Listeners
use Illuminate\Auth\Events\Login;
use App\Listeners\SendAdminLoginAlert;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 1. Registrar Observador (Notificaciones de estado)
        Reserva::observe(ReservaObserver::class);

        // 2. Registrar Policies (Seguridad de Modelos)
        Gate::policy(Cancha::class, CanchaPolicy::class);
        Gate::policy(Reserva::class, ReservaPolicy::class);

        // 3. Registrar Alerta de Login (Seguridad Admin)
        // En AppServiceProvider usamos Event::listen en lugar del array $listen
        Event::listen(
            Login::class,
            SendAdminLoginAlert::class
        );
    }
}