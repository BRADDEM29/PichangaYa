<?php

namespace App\Observers;

use App\Models\Reserva;
use App\Models\User;
use App\Mail\ReservaStatusChanged;
use App\Notifications\NuevaReservaNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class ReservaObserver
{
    /**
     * Se ejecuta cuando se CREA una reserva.
     */
    public function created(Reserva $reserva): void
    {
        // 1. Obtener destinatarios: Dueño y Admins
        $owner = $reserva->cancha->user;
        $admins = User::where('role', 'admin')->get();
        
        // 2. Notificar a ADMIN y DUEÑO (Para que sepan que hay venta)
        Notification::send($admins->merge([$owner]), new NuevaReservaNotification($reserva));

        // 3. 🟢 CORRECCIÓN: Notificar también al CLIENTE (Para que le salga en su campanita)
        // Usamos la misma notificación, el front-end ya sabe cómo mostrarla
        $reserva->user->notify(new NuevaReservaNotification($reserva));
    }

    /**
     * Se ejecuta cuando se ACTUALIZA una reserva.
     */
    public function updated(Reserva $reserva): void
    {
        if ($reserva->isDirty('status')) {
            
            // Si sigue pendiente, no enviamos correo (ya tiene la notificación en la web)
            if ($reserva->status === 'pending') {
                return;
            }

            // Enviar CORREO al cliente cuando cambia de estado (Confirmado/Cancelado)
            try {
                Mail::to($reserva->user->email)->send(new ReservaStatusChanged($reserva));
                
                // 🟢 Opcional: También enviar notificación a la campanita avisando del cambio
                // Crearías una nueva clase Notification 'EstadoReservaCambiado' si quisieras
                // $reserva->user->notify(new EstadoReservaNotification($reserva));
                
            } catch (\Exception $e) {
                \Log::error('Error enviando correo reserva: ' . $e->getMessage());
            }
        }
    }
}