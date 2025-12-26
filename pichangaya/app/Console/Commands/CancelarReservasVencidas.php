<?php
// C:\laragon\www\PichangaYa\pichangaya\app\Console\Commands\CancelarReservasVencidas.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reserva;
use Carbon\Carbon;
// 👇 IMPORTANTE: Importamos la notificación
use App\Notifications\ReservaCancelada;

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
            
            // 🔔 Enviar alerta al usuario sobre la cancelación automática
            try {
                $reserva->user->notify(new ReservaCancelada($reserva));
            } catch (\Exception $e) {
                $this->error("Error notificando reserva #{$reserva->id}: " . $e->getMessage());
            }

            $this->info("Reserva #{$reserva->id} cancelada. Strike aplicado y notificación enviada.");
        }

        $this->info("Proceso finalizado. Se cancelaron $count reservas.");
    }
}