<?php
// C:\laragon\www\PichangaYa\pichangaya\app\Notifications\ReservaEstadoActualizado.php
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
        $canchaNombre = $this->reserva->cancha->name;
        
        // Valores por defecto
        $titulo = 'Actualización de Reserva';
        $mensaje = 'El estado de tu reserva ha cambiado.';
        $icono = 'info'; 
        $color = 'text-blue-500';

        switch ($estado) {
            case 'advance_paid':
                $titulo = '🟡 Adelanto Recibido';
                $mensaje = "Se confirmó el adelanto para tu reserva en $canchaNombre. Queda un saldo pendiente.";
                $icono = 'currency_exchange';
                $color = 'text-yellow-600';
                break;

            case 'fully_paid':
                $titulo = '🟢 Reserva Confirmada';
                $mensaje = "¡Listo! Tu reserva en $canchaNombre está pagada al 100%.";
                $icono = 'check_circle';
                $color = 'text-green-600';
                break;

            // 🔴 LÓGICA DE CANCELACIÓN (ROJO)
            case 'cancelled':
                $titulo = '🔴 Reserva Cancelada';
                $mensaje = "Lo sentimos, tu reserva en $canchaNombre ha sido cancelada.";
                $icono = 'cancel'; // Icono de X (Material Icons)
                $color = 'text-red-600'; // Color Rojo intenso
                break;
        }

        return [
            'titulo'     => $titulo,
            'mensaje'    => $mensaje,
            'icono'      => $icono,
            'color'      => $color,
            'reserva_id' => $this->reserva->id,
            // Al hacer clic, llevamos al historial de reservas del usuario
            'url'        => route('reservas.user.index'), 
            'expiry_ts'  => null, // No necesita temporizador
        ];
    }
}