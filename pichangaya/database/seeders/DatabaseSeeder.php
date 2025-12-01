<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // <--- AGREGAR ESTA LÍNEA ES CRUCIAL

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Aquí llamamos a tu archivo de Roles (Admin, Dueño, Cliente)
        // Esto está PERFECTO, es lo que necesitábamos.
        $this->call(UserSeeder::class);

        // 2. Distritos y Deportes (NUEVO)
    $this->call([
        DistrictSeeder::class,
        SportSeeder::class,
        BusinessSeeder::class, 
    ]);
        // 2. Usuario de prueba adicional (Opcional)
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                // CORRECCIÓN: La contraseña SIEMPRE debe ir con Hash::make
                'password' => Hash::make('password'), 
                'email_verified_at' => now(),
                // Como ya tienes roles, es bueno definir qué es este usuario (opcional)
                'role' => 'user', 
            ]
        );
    }
}