<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Reserva;

class ReservaCancelada extends Notification
{
    use Queueable;

    protected $reserva;

    public function __construct(Reserva $reserva)
    {
        $this->reserva = $reserva;
    }

    // 🟢 AQUÍ ESTÁ LA CLAVE: 'database' hace que salga en la campanita
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    // Correo electrónico
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('❌ Reserva Cancelada - PichangaYa')
                    ->line('Tu reserva en ' . $this->reserva->cancha->name . ' ha sido cancelada.')
                    ->line('Motivo: Tiempo de espera agotado o cancelación manual.')
                    ->action('Ver mis reservas', route('reservas.user.index'));
    }

    // 🔔 Base de Datos (Campanita)
    public function toArray(object $notifiable): array
    {
        return [
            'titulo' => 'Reserva Cancelada',
            'mensaje' => 'La reserva en ' . $this->reserva->cancha->name . ' ha sido cancelada.',
            'icono'  => 'cancel', // Usaremos esto para el diseño rojo
            'url'    => route('reservas.user.index')
        ];
    }
}