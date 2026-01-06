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
        // 1. CÁLCULO GLOBAL DEL TEMPORIZADOR
        // =====================================================================
        $expiryObj = $this->reserva->created_at->addMinutes(10);
        $expiryTimestamp = $expiryObj->timestamp * 1000; // JS usa milisegundos
        $horaExpiracion = $expiryObj->format('H:i');

        // Datos visuales base
        $canchaNombre = $this->reserva->cancha->name;
        
        // Inicializamos variables
        $titulo = '';
        $mensaje = '';
        $icono = ''; // Enviaremos una clave de texto (ej: 'clock'), no un emoji
        $color = '';
        $url = '#';

        // =====================================================================
        // 2. Lógica de TEXTO según el ROL
        // =====================================================================
        
        // CASO A: Es el CLIENTE (El que debe pagar)
        if ($notifiable->id === $this->reserva->user_id) {
            // TEXTO LIMPIO (Sin emojis)
            $titulo = 'Pago Pendiente (10 min)';
            $mensaje = "Tu reserva en $canchaNombre expira a las $horaExpiracion.";
            $icono = 'clock'; // Clave para icono de reloj
            $color = 'text-orange-500';
            $url = route('reservas.user.index');
        } 
        // CASO B: Es ADMIN o DUEÑO
        else {
            // TEXTO LIMPIO
            $titulo = 'Nueva Reserva (Pendiente)';
            $mensaje = "Cliente: {$this->reserva->user->name} | $canchaNombre | Expira: $horaExpiracion";
            $icono = 'currency_exchange'; // Clave para icono de dinero
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
            
            // Dato crítico para el temporizador visual
            'expiry_ts'  => $expiryTimestamp, 
            'created_at' => now(),
        ];
    }
}