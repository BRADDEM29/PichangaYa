<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Reserva;

class ReservaEstadoActualizado extends Notification
{
    use Queueable;

    public $reserva;

    public function __construct(Reserva $reserva)
    {
        $this->reserva = $reserva;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $estado = $this->reserva->status;
        
        // Valores por defecto
        $titulo = 'Actualización de Reserva';
        $mensaje = 'El estado de tu reserva ha cambiado.';
        $icono = 'info'; 
        $color = 'text-blue-500';

        switch ($estado) {
            case 'advance_paid':
                $titulo = '🟡 Pago Adelantado Confirmado';
                // MENSAJE ESPECÍFICO QUE PEDISTE:
                $mensaje = "Se ha registrado tu pago adelantado. Recuerda pagar el saldo restante en la cancha.";
                $icono = 'currency_exchange'; // Icono de dinero/cambio
                $color = 'text-yellow-600';
                break;

            case 'fully_paid':
                $titulo = '🟢 Pago Completo Exitoso';
                $mensaje = "¡Todo listo! Tu reserva en {$this->reserva->cancha->name} está pagada al 100%.";
                $icono = 'check_circle';
                $color = 'text-green-600';
                break;

            case 'cancelled':
                $titulo = '🔴 Reserva Cancelada';
                $mensaje = "Tu reserva ha sido cancelada.";
                $icono = 'cancel';
                $color = 'text-red-600';
                break;
        }

        return [
            'reserva_id' => $this->reserva->id,
            'titulo' => $titulo,
            'mensaje' => $mensaje,
            'icono' => $icono,
            'color' => $color,
            // Esta URL lleva a mis reservas y baja automáticamente a la tarjeta de esa reserva
            'url' => route('reservas.user.index') . '#reserva-' . $this->reserva->id, 
        ];
    }
}