<?php
// C:\laragon\www\PichangaYa\pichangaya\app\Observers\ReservaObserver.php

namespace App\Observers;

use App\Models\Reserva;
use App\Models\User;
use App\Mail\ReservaStatusChanged;
use Illuminate\Support\Facades\Mail;
// use Illuminate\Support\Facades\Notification; // 🟢 Ya no es necesario aquí para 'created'

class ReservaObserver
{
    /**
     * Se ejecuta cuando se CREA una reserva.
     */
    public function created(Reserva $reserva): void
    {
        // 🔴 ANTES: Aquí se enviaban notificaciones, causando duplicidad con el Controlador.
        // ✅ AHORA: Lo dejamos vacío. El ReservaController ya se encarga de notificar 
        // al Cliente, Dueño y Admins correctamente.
    }

    /**
     * Se ejecuta cuando se ACTUALIZA una reserva.
     */
    public function updated(Reserva $reserva): void
    {
        // MANTENEMOS ESTA LÓGICA (Envío de correos al cambiar estado)
        // Esto no genera notificaciones en la campanita, solo emails, así que está bien.
        if ($reserva->isDirty('status')) {
            
            // Si sigue pendiente, no enviamos correo (ya tiene la notificación en la web)
            if ($reserva->status === 'pending') {
                return;
            }

            // Enviar CORREO al cliente cuando cambia de estado (Confirmado/Cancelado)
            try {
                // Verificamos que exista la clase de Mail antes de enviar
                Mail::to($reserva->user->email)->send(new ReservaStatusChanged($reserva));
                
            } catch (\Exception $e) {
                \Log::error('Error enviando correo reserva: ' . $e->getMessage());
            }
        }
    }
}