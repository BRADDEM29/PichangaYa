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
            $nuevoEstado = $reserva->status;

            // =================================================================
            // 1. LIMPIEZA DE NOTIFICACIONES (Sincronización Campanita)
            // =================================================================
            // Si la reserva deja de estar pendiente (se paga o cancela),
            // buscamos la notificación de "Pago Pendiente" y la marcamos como leída.
            if ($nuevoEstado !== 'pending') {
                $user->unreadNotifications
                     ->filter(function ($notification) use ($reserva) {
                         // Buscamos notificaciones asociadas a esta reserva específica
                         return isset($notification->data['reserva_id']) && 
                                $notification->data['reserva_id'] === $reserva->id &&
                                isset($notification->data['expiry_ts']); // Que sea la del temporizador
                     })
                     ->markAsRead();
            }

            // =================================================================
            // 2. SISTEMA DE STRIKES (Solo para Usuarios, no Admins/Owners)
            // =================================================================
            // Solo aplicamos castigos a usuarios normales, no al staff
            if ($user->role === 'user') {

                // CASO A: ÉXITO (PAGÓ) -> Perdonamos pecados
                // Si el usuario completa el pago (total o parcial), perdonamos sus pecados anteriores.
                if (in_array($nuevoEstado, ['advance_paid', 'fully_paid'])) {
                    if ($user->consecutive_cancellations > 0) {
                        $user->consecutive_cancellations = 0;
                        $user->save();
                    }
                }

                // CASO B: CANCELACIÓN -> Castigo (Aumentar strikes)
                if ($nuevoEstado === 'cancelled') {
                    $user->increment('consecutive_cancellations');
                    
                    // --- 3er STRIKE: ADVERTENCIA DEL TERROR ---
                    // Guardamos una variable de sesión "flash" para mostrar el Overlay Rojo en la vista
                    if ($user->consecutive_cancellations == 3) {
                        session()->flash('warning_strike_level', 3);
                        
                        // Opcional: Notificar también a los admins
                        $admins = User::where('role', 'admin')->get();
                        if($admins->count() > 0) {
                            Notification::send($admins, new AlertaConductaUsuario($user, 3));
                        }
                    }

                    // --- 4to STRIKE: BLOQUEO DEFINITIVO ---
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