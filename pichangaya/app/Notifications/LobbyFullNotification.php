<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Lobby;

class LobbyFullNotification extends Notification
{
    use Queueable;

    public $lobby;
    public $playerCount;
    public $maxPlayers;

    public function __construct(Lobby $lobby, $playerCount = null, $maxPlayers = null)
    {
        $this->lobby = $lobby;
        $this->playerCount = $playerCount ?? $lobby->slots()->count();
        $this->maxPlayers = $maxPlayers ?? $lobby->max_slots;
    }

    public function via($notifiable)
    {
        return ['database']; 
    }

    public function toDatabase($notifiable)
    {
        return [
            'titulo'  => '⚠️ SALA LLENA',
            // Mensaje dinámico con conteo real
            'mensaje' => "El lobby está completo ({$this->playerCount}/{$this->maxPlayers}). Confirma tu asistencia ahora.",
            'icono'   => 'clock', 
            'url'     => route('lobby.show', $this->lobby->id),
            'id'      => $this->lobby->id,
            'color'   => 'text-yellow-500',
            'action_required' => true 
        ];
    }
}