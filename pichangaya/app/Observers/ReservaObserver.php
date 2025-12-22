<?php
// C:\laragon\www\PichangaYa\pichangaya\app\Observers\ReservaObserver.php

namespace App\Observers;

use App\Models\Reserva;
use App\Models\User;
use App\Mail\ReservaStatusChanged;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use App\Notifications\AlertaConductaUsuario; // 🟢 Notificación de seguridad

class ReservaObserver
{
    /**
     * Se ejecuta cuando se CREA una reserva.
     */
    public function created(Reserva $reserva): void
    {
        // ✅ VACÍO INTENCIONALMENTE
        // El ReservaController ya maneja las notificaciones de creación (NuevaReservaNotification)
        // para evitar duplicidad y excluir al propio usuario admin si él crea la reserva.
    }

    /**
     * Se ejecuta cuando se ACTUALIZA una reserva.
     */
    public function updated(Reserva $reserva): void
    {
        // Detectar cambios de estado
        if ($reserva->isDirty('status')) {
            
            $user = $reserva->user;

            // =========================================================
            // 🔒 LÓGICA DE SEGURIDAD (Strikes y Bloqueos)
            // =========================================================

            // CASO 1: ÉXITO (Resetear contador de mal comportamiento)
            // Si el usuario completa el pago (total o parcial), perdonamos sus pecados anteriores.
            if (in_array($reserva->status, ['confirmed', 'advance_paid', 'fully_paid'])) {
                if ($user->consecutive_cancellations > 0) {
                    $user->consecutive_cancellations = 0;
                    $user->save();
                }
            }

            // CASO 2: CANCELACIÓN (Aumentar strikes)
            if ($reserva->status === 'cancelled') {
                $user->increment('consecutive_cancellations');
                
                // REGLA DEL 3ER STRIKE (Advertencia Administrativa)
                if ($user->consecutive_cancellations == 3) {
                    $admins = User::where('role', 'admin')->get();
                    if($admins->count() > 0) {
                        Notification::send($admins, new AlertaConductaUsuario($user, 3));
                    }
                }

                // REGLA DEL 4TO STRIKE (Bloqueo Definitivo)
                if ($user->consecutive_cancellations >= 4) {
                    $user->is_blocked = true;
                    $user->save();
                    
                    // Notificar a Admins del bloqueo automático
                    $admins = User::where('role', 'admin')->get();
                    if($admins->count() > 0) {
                        Notification::send($admins, new AlertaConductaUsuario($user, 4));
                    }
                }
            }

            // =========================================================
            // 📧 ENVÍO DE CORREOS (Lógica Original)
            // =========================================================

            // Si sigue pendiente, no enviamos correo (ya tiene la notificación en la web)
            if ($reserva->status === 'pending') {
                return;
            }

            // Enviar CORREO al cliente cuando cambia de estado (Confirmado/Cancelado)
            try {
                Mail::to($reserva->user->email)->send(new ReservaStatusChanged($reserva));
            } catch (\Exception $e) {
                \Log::error('Error enviando correo reserva: ' . $e->getMessage());
            }
        }
    }
}