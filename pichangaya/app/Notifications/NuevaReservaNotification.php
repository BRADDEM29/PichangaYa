<?php
// C:\laragon\www\PichangaYa\pichangaya\app\Notifications\NuevaReservaNotification.php

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
        // =====================================================================
        // 🟢 1. CÁLCULO GLOBAL DEL TEMPORIZADOR (Para Todos: Cliente, Admin, Dueño)
        // =====================================================================
        // Calculamos esto AQUÍ afuera para que el Admin también reciba el dato 'expiry_ts'
        // y el controlador pueda proteger la notificación de ser borrada.
        $expiryObj = $this->reserva->created_at->addMinutes(10);
        $expiryTimestamp = $expiryObj->timestamp * 1000; // JS usa milisegundos
        $horaExpiracion = $expiryObj->format('H:i');

        // Datos visuales base
        $canchaNombre = $this->reserva->cancha->name;
        $fechaReserva = $this->reserva->start_time->format('d/m H:i');
        
        // Inicializamos variables
        $titulo = '';
        $mensaje = '';
        $icono = '';
        $color = '';
        $url = '#';

        // =====================================================================
        // 2. Lógica de TEXTO según el ROL
        // =====================================================================
        
        // CASO A: Es el CLIENTE (El que debe pagar)
        if ($notifiable->id === $this->reserva->user_id) {
            $titulo = '⏳ Pago Pendiente (10 min)';
            $mensaje = "Tu reserva en $canchaNombre expira a las $horaExpiracion.";
            $icono = 'hourglass_empty'; 
            $color = 'text-orange-500';
            $url = route('reservas.user.index');
        } 
        // CASO B: Es ADMIN o DUEÑO (Información + Timer visual)
        else {
            $titulo = '📅 Nueva Reserva (Pendiente)';
            // Agregamos la hora de expiración al mensaje del admin también para claridad
            $mensaje = "Cliente: {$this->reserva->user->name} | $canchaNombre | Expira: $horaExpiracion";
            $icono = 'currency_exchange';
            $color = 'text-indigo-600';

            // Rutas inteligentes según rol
            if ($notifiable->role === 'admin') {
                $identificador = $this->reserva->cancha->slug ?? $this->reserva->cancha->id;
                // El Admin va al panel de esa cancha
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
            
            // 🟢 IMPORTANTE: Ahora 'expiry_ts' viaja siempre, 
            // permitiendo al Admin ver el cronómetro y evitando el borrado accidental.
            'expiry_ts'  => $expiryTimestamp, 
            'created_at' => now(),
        ];
    }
}