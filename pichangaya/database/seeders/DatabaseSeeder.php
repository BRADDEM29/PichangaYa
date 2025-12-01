<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Llamamos a todos los seeders en UN SOLO GRUPO
        $this->call([
            UserSeeder::class,      // 1. Usuarios (Admin, Dueño)
            DistrictSeeder::class,  // 2. Distritos
            SportSeeder::class,     // 3. Deportes
            CanchaSeeder::class,    // 4. Canchas (necesita lo anterior)
        ]);

        // Usuario de prueba adicional (Opcional, si quieres tenerlo aparte)
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role' => 'user',
            ]
        );
    }
}