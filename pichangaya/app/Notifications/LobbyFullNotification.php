<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Lobby;

class LobbyFullNotification extends Notification
{
    use Queueable;

    public $lobby;

    public function __construct(Lobby $lobby)
    {
        $this->lobby = $lobby;
    }

    public function via($notifiable)
    {
        return ['database']; 
    }

    public function toDatabase($notifiable)
    {
        // Obtenemos los datos frescos usando el nuevo helper del modelo
        $total = $this->lobby->max_slots;
        $confirmados = $this->lobby->confirmed_count;
        $deporte = strtoupper($this->lobby->sport->name ?? 'DEPORTE');

        return [
            // Título limpio sin emojis
            'titulo'  => "SALA LLENA - {$deporte}",
            
            // Mensaje informativo con conteo real
            'mensaje' => "La sala ha alcanzado {$total} jugadores. Estado de confirmación: {$confirmados}/{$total}. Ingresa para confirmar.",
            
            // Icono SVG (clave de texto para que el frontend ponga el SVG)
            'icono'   => 'clock', 
            'url'     => route('lobby.show', $this->lobby->id),
            'id'      => $this->lobby->id,
            'color'   => 'text-yellow-500',
            'action_required' => true 
        ];
    }
}