<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Lobby;

class ReadyForReservationNotification extends Notification
{
    use Queueable;

    public $lobby;
    public $confirmedCount;
    public $maxPlayers;

    public function __construct(Lobby $lobby, int $confirmedCount, int $maxPlayers)
    {
        $this->lobby = $lobby;
        $this->confirmedCount = $confirmedCount;
        $this->maxPlayers = $maxPlayers;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            // Título limpio indicando éxito
            'titulo'  => 'EQUIPO LISTO PARA JUGAR',
            
            // Mensaje confirmando que todos están listos
            'mensaje' => "Todos los jugadores han confirmado ({$this->confirmedCount}/{$this->maxPlayers}). Como líder, procede a la reserva.",
            
            // Icono SVG de éxito
            'icono'   => 'check_circle', 
            'url'     => route('lobby.show', ['lobby' => $this->lobby->id]), 
            'id'      => $this->lobby->id,
            'color'   => 'text-green-500',
        ];
    }
}