<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;
// 🟢 IMPORTANTE: Importar la clase Password
use Illuminate\Validation\Rules\Password;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20'], 
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
            
            // 🟢 REGLAS DE CONTRASEÑA NIVEL "MEDIO"
            'password' => [
                'required',
                'string',
                'confirmed',
                Password::min(8)        // Mínimo 8 caracteres
                    ->mixedCase()       // Mayúsculas y Minúsculas
                    ->numbers()         // Números
                    // ->symbols()      // NO lo ponemos, para que "Medio" sea suficiente
            ],
        ])->validate();

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'phone' => $input['phone'],
            'password' => Hash::make($input['password']),
        ]);
    }
}