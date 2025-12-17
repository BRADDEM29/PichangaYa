<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Reserva;

class NuevaReservaNotification extends Notification
{
    use Queueable;

    public $reserva;

    public function __construct(Reserva $reserva)
    {
        $this->reserva = $reserva;
    }

    public function via(object $notifiable): array
    {
        return ['database']; 
    }

    public function toArray(object $notifiable): array
    {
        // 🟢 LÓGICA INTELIGENTE: Mensaje distinto según quién lo recibe
        $mensaje = '';
        
        if ($notifiable->id === $this->reserva->user_id) {
            // Es el CLIENTE
            $mensaje = '⏳ Tienes una reserva pendiente por pagar.';
        } else {
            // Es el ADMIN o DUEÑO
            $mensaje = 'Nueva reserva recibida de ' . $this->reserva->user->name;
        }

        return [
            'reserva_id' => $this->reserva->id,
            'user_name' => $this->reserva->user->name,
            'cancha_name' => $this->reserva->cancha->name,
            'amount' => $this->reserva->total_price,
            'message' => $mensaje, // Usamos el mensaje personalizado
            'time' => now()->toDateTimeString(),
        ];
    }
}