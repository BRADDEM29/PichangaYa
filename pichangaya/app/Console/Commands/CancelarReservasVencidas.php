<?php
// C:\laragon\www\PichangaYa\pichangaya\app\Console\Commands\CancelarReservasVencidas.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reserva;
use Carbon\Carbon;

class CancelarReservasVencidas extends Command
{
    // Puedes mantener tu firma antigua si ya la tienes configurada en el Cron
    protected $signature = 'reservas:expirar'; 
    protected $description = 'Cancela reservas pendientes de > 10 min y APLICA STRIKES';

    public function handle()
    {
        // Buscamos las reservas vencidas (damos 2 min de gracia por latencia)
        $reservas = Reserva::where('status', 'pending')
            ->where('created_at', '<', Carbon::now()->subMinutes(10))
            ->get(); // IMPORTANTE: Usar get() para traer los modelos

        $count = 0;

        foreach ($reservas as $reserva) {
            // Al guardar así, se activa el OBSERVER y cuenta el Strike
            $reserva->status = 'cancelled';
            $reserva->save();
            $count++;
            
            $this->info("Reserva #{$reserva->id} cancelada. Strike aplicado.");
        }

        $this->info("Proceso finalizado. Se cancelaron $count reservas.");
    }
}