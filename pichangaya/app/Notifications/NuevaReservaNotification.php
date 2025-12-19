<?php
//C:\laragon\www\PichangaYa\pichangaya\app\Notifications\NuevaReservaNotification.php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Reserva;
use Carbon\Carbon;

class NuevaReservaNotification extends Notification
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
        // 1. Datos comunes
        $canchaNombre = $this->reserva->cancha->name;
        $fechaReserva = $this->reserva->start_time->format('d/m H:i');
        
        // Inicializamos variables
        $titulo = '';
        $mensaje = '';
        $icono = '';
        $color = '';
        $url = '#';
        $expiryTimestamp = null; // 🟢 Variable para el temporizador JS

        // 2. Lógica según QUIÉN recibe la notificación
        
        // CASO A: Es el CLIENTE (El dueño de la reserva)
        if ($notifiable->id === $this->reserva->user_id) {
            $expiryObj = $this->reserva->created_at->addMinutes(10);
            $horaExpiracion = $expiryObj->format('H:i');
            
            // 🟢 JS usa milisegundos, por eso multiplicamos por 1000
            $expiryTimestamp = $expiryObj->timestamp * 1000; 
            
            $titulo = '⏳ Pago Pendiente (10 min)';
            $mensaje = "Tu reserva en $canchaNombre expira a las $horaExpiracion.";
            $icono = 'hourglass_empty'; // Icono especial
            $color = 'text-orange-500';
            $url = route('reservas.user.index');
        } 
        // CASO B: Es ADMIN o DUEÑO
        else {
            $titulo = '📅 Nueva Reserva Recibida';
            $mensaje = "Nueva reserva de {$this->reserva->user->name} en $canchaNombre el $fechaReserva.";
            $icono = 'currency_exchange';
            $color = 'text-indigo-600';

            // Rutas inteligentes según rol
            if ($notifiable->role === 'admin') {
                $identificador = $this->reserva->cancha->slug ?? $this->reserva->cancha->id;
                $url = route('admin.canchas.reservas.index', $identificador);
            } elseif ($notifiable->role === 'owner') {
                $url = route('owner.reservas.index');
            }
        }

        // 3. Retorno de datos
        return [
            'titulo'     => $titulo,
            'mensaje'    => $mensaje,
            'icono'      => $icono,
            'color'      => $color,
            'url'        => $url,
            'reserva_id' => $this->reserva->id,
            'expiry_ts'  => $expiryTimestamp, // 🟢 Dato clave para el JS
            'created_at' => now(),
        ];
    }
}