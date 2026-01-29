<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification; // ✅ Corregido (solo una barra)
use App\Models\Lobby;

class ReadyForReservationNotification extends Notification
{
    use Queueable;

    public $lobby;

    /**
     * Create a new notification instance.
     */
    public function __construct(Lobby $lobby)
    {
        $this->lobby = $lobby;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        return [
            'titulo'  => '👑 ¡EQUIPO LISTO!',
            'mensaje' => "Todos han confirmado. Como líder, debes proceder a la reserva de la cancha ahora.",
            'icono'   => 'check_circle', // Asegúrate de tener este icono manejado en tu vista
            // ✅ Corregido: Pasamos el parámetro de ruta explícitamente para evitar errores
            'url'     => route('lobby.show', ['lobby' => $this->lobby->id]), 
            'id'      => $this->lobby->id,
            'color'   => 'text-green-500',
        ];
    }
}