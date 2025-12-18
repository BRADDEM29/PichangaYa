<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Reserva;

class ReservaEstadoActualizado extends Notification
{
    use Queueable;

    public $reserva;

    /**
     * Create a new notification instance.
     */
    public function __construct(Reserva $reserva)
    {
        $this->reserva = $reserva;
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
    public function toArray($notifiable)
    {
        $estado = $this->reserva->status;
        
        // 1. Valores por defecto
        $titulo = 'Actualización de Reserva';
        $mensaje = 'El estado de tu reserva ha cambiado.';
        $icono = 'info'; 
        $color = 'text-blue-500';

        // 2. Lógica personalizada según el estado
        switch ($estado) {
            case 'advance_paid':
                $titulo = '🟡 Pago Adelantado Confirmado';
                $mensaje = "Se ha registrado tu pago adelantado de S/ " . $this->reserva->payment_amount . ". Recuerda pagar el saldo restante en la cancha.";
                $icono = 'currency_exchange';
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
                $mensaje = "Tu reserva ha sido cancelada. Si crees que es un error, contáctanos.";
                $icono = 'cancel';
                $color = 'text-red-600';
                break;
        }

        // 3. Array final compatible con el sistema de notificaciones
        return [
            'titulo'     => $titulo,
            'mensaje'    => $mensaje,
            'icono'      => $icono,
            'color'      => $color, // Opcional, pero útil para estilos
            'reserva_id' => $this->reserva->id,
            
            // IMPORTANTE: La URL de redirección.
            // Apuntamos a "Mis Reservas" ya que 'reservas.show' no existe para clientes.
            'url'        => route('reservas.user.index'), 
        ];
    }
}