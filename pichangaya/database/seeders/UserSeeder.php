<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Admin
        User::create([
            'name' => 'Admin Yana Katari',
            'email' => 'admin@yanakatari.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '999999999', // 🟢 Teléfono Agregado
        ]);

        // 2. Dueño
        User::create([
            'name' => 'Juan Dueño',
            'email' => 'dueno@yanakatari.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
            'phone' => '999999999', // 🟢 Teléfono Agregado
        ]);

        // 3. Usuario Normal
        User::create([
            'name' => 'Cliente Deportivo',
            'email' => 'cliente@yanakatari.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'phone' => '999999999', // 🟢 Teléfono Agregado
        ]);
    }
}