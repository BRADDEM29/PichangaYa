<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Lobby;
use App\Models\LobbySlot;
use Carbon\Carbon;

class CheckLobbyExpirations extends Command
{
    protected $signature = 'lobby:check-expirations';
    protected $description = 'Expulsa usuarios no confirmados (2h) y elimina lobbies viejos (2d)';

    public function handle()
    {
        // 1. REGLA DE 2 HORAS: Expulsar jugadores NO confirmados en salas llenas
        $lobbiesConfirming = Lobby::where('status', 'confirming')->get();

        foreach ($lobbiesConfirming as $lobby) {
            // Si el lobby entró en estado 'confirming' hace más de 2 horas
            if ($lobby->updated_at < Carbon::now()->subHours(2)) {
                
                // Buscar slots sin confirmar
                $lazySlots = $lobby->slots()->whereNull('confirmed_at')->get();
                
                foreach ($lazySlots as $slot) {
                    $slot->delete(); // ¡KICK!
                    $this->info("Usuario {$slot->user_id} expulsado del lobby {$lobby->id} por inactividad.");
                }

                // Si expulsamos a alguien, la sala ya no está llena, vuelve a searching
                if ($lazySlots->count() > 0) {
                    $lobby->update(['status' => 'searching']);
                }
            }
        }

        // 2. REGLA DE 2 DÍAS: Eliminar lobbies zombies
        $oldLobbies = Lobby::where('created_at', '<', Carbon::now()->subDays(2))
                           ->where('status', '!=', 'playing') // No borrar si ya están jugando
                           ->get();

        foreach ($oldLobbies as $lobby) {
            $lobby->delete();
            $this->info("Lobby {$lobby->id} eliminado por antigüedad (48h).");
        }
    }
}