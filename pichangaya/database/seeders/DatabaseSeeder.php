<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,      // 1. Usuarios
            DistrictSeeder::class,  // 2. Distritos
            SportSeeder::class,     // 3. Deportes
            ServiceSeeder::class,   // 4. Servicios (ANTES de Canchas)
            CanchaSeeder::class,    // 5. Canchas (Usa todo lo anterior)
        ]);
    }
}