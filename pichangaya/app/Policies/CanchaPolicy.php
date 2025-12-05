<?php

namespace App\Policies;

use App\Models\Cancha;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CanchaPolicy
{
    /**
     * Determina si el usuario puede ver el listado de canchas (Panel).
     * Admin y Dueños pueden ver sus propios paneles.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isOwner();
    }

    /**
     * Determina si el usuario puede ver una cancha específica.
     * Público en general puede ver (implementado en controller público), 
     * pero para edición/detalle interno verificamos.
     */
    public function view(User $user, Cancha $cancha): bool
    {
        return true; // Todos pueden ver el detalle público
    }

    /**
     * Determina si el usuario puede crear canchas.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isOwner();
    }

    /**
     * Determina si el usuario puede actualizar la cancha.
     * REGLA DE ORO: Solo el Admin o el Dueño REAL de la cancha.
     */
    public function update(User $user, Cancha $cancha): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        // El usuario debe ser dueño Y además ser el propietario de ESTA cancha
        return $user->isOwner() && $user->id === $cancha->user_id;
    }

    /**
     * Determina si el usuario puede eliminar la cancha.
     */
    public function delete(User $user, Cancha $cancha): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isOwner() && $user->id === $cancha->user_id;
    }
}