<?php

namespace App\Actions\Jetstream;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Laravel\Jetstream\Contracts\DeletesUsers;

class DeleteUser implements DeletesUsers
{
    /**
     * Delete the given user.
     */
    public function delete(User $user): void
    {
        DB::transaction(function () use ($user) {
            // 1. Borrar foto
            $user->deleteProfilePhoto();

            // 2. TODO: Descomentar para la App Móvil
            // $user->tokens->each->delete(); 

            // 4. Soft Delete
            $user->delete();
        });
    }
}