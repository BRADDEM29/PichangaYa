<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reserva;
use Carbon\Carbon;

class CancelarReservasVencidas extends Command
{
    protected $signature = 'reservas:expirar';
    protected $description = 'Cancela reservas pendientes que tienen más de 10 minutos de antigüedad';

    public function handle()
    {
        // Buscar reservas pendientes creadas hace más de 10 minutos
        $afectadas = Reserva::where('status', 'pending')
            ->where('created_at', '<', Carbon::now()->subMinutes(10))
            ->update(['status' => 'cancelled']);

        $this->info("Se cancelaron $afectadas reservas vencidas.");
    }
}