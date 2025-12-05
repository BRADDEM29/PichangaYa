<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

// Modelos
use App\Models\Cancha;
use App\Models\Reserva;

// Policies
use App\Policies\CanchaPolicy;
use App\Policies\ReservaPolicy;

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
        // Registro manual de Policies para asegurar la seguridad
        Gate::policy(Cancha::class, CanchaPolicy::class);
        Gate::policy(Reserva::class, ReservaPolicy::class);
    }
}