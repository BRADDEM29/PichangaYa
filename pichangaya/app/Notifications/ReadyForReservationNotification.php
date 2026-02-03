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

    /**
     * Recibimos el lobby y los contadores para personalizar el mensaje.
     */
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
        // Mensaje dinámico con el conteo exacto (Ej: "14/14 jugadores listos")
        return [
            'titulo'  => 'EQUIPO COMPLETO',
            'mensaje' => "Todos han confirmado ({$this->confirmedCount}/{$this->maxPlayers}). Como líder, inicia la reserva ahora.",
            'icono'   => 'check_circle', 
            'url'     => route('lobby.show', ['lobby' => $this->lobby->id]), 
            'id'      => $this->lobby->id,
            'color'   => 'text-green-500',
        ];
    }
}