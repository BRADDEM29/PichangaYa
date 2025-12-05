<?php

namespace App\Policies;

use App\Models\Reserva;
use App\Models\User;

class ReservaPolicy
{
    /**
     * Determina quién puede ver una reserva específica.
     */
    public function view(User $user, Reserva $reserva): bool
    {
        // 1. El Admin lo ve todo
        if ($user->isAdmin()) {
            return true;
        }

        // 2. El Cliente que hizo la reserva puede verla
        if ($user->id === $reserva->user_id) {
            return true;
        }

        // 3. El Dueño de la cancha reservada puede verla
        // (Asumiendo que Reserva pertenece a Cancha y Cancha pertenece a User)
        if ($reserva->cancha && $reserva->cancha->user_id === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Determina quién puede cancelar una reserva.
     */
    public function cancel(User $user, Reserva $reserva): bool
    {
        // Admin puede cancelar siempre
        if ($user->isAdmin()) {
            return true;
        }

        // El dueño de la cancha puede cancelar (por mantenimiento, etc)
        if ($reserva->cancha && $reserva->cancha->user_id === $user->id) {
            return true;
        }

        // El usuario puede cancelar SOLO SI es suya 
        // (Opcional: podrías agregar lógica de tiempo, ej: '&& !$reserva->hasStarted()')
        if ($user->id === $reserva->user_id) {
            return true;
        }

        return false;
    }

    /**
     * Determina quién puede actualizar el estado (ej: confirmar pago).
     * Generalmente solo el Dueño o Admin.
     */
    public function updateStatus(User $user, Reserva $reserva): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        // Solo el dueño de la cancha recibe el dinero/gestiona
        return $reserva->cancha && $reserva->cancha->user_id === $user->id;
    }
}