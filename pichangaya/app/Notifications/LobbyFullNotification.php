<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
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
        // Puedes agregar 'mail' aquí si quieres enviar correos también
        return ['database']; 
    }

    public function toDatabase($notifiable)
    {
        return [
            // CAMBIO: Mensaje de urgencia y límite de tiempo (2 horas)
            'titulo'  => '⚠️ SALA LLENA - ACCIÓN REQUERIDA',
            'mensaje' => "El lobby está completo. Tienes 2 HORAS para confirmar tu asistencia o serás expulsado automáticamente.",
            'icono'   => 'clock', // Cambiado a reloj para indicar cuenta regresiva
            'url'     => route('lobby.show', $this->lobby->id),
            'id'      => $this->lobby->id,
            'color'   => 'text-yellow-500', // Color amarillo de advertencia
            'action_required' => true // Mantenemos este flag para resaltar la notificación en la UI
        ];
    }
}