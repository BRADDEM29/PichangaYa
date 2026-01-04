<?php
// C:\laragon\www\PichangaYa\pichangaya\database\seeders\SportSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sport;

class SportSeeder extends Seeder
{
    public function run(): void
    {
        $sports = [
            // Claves deben coincidir con config/icons.php -> 'sports'
            ['name' => 'Fútbol 5', 'icon' => 'futbol'],
            ['name' => 'Fútbol 7', 'icon' => 'futbol'],
            ['name' => 'Fútbol 11', 'icon' => 'futbol'], // Usamos 'futbol' genérico o crea uno específico si deseas
            ['name' => 'Vóley', 'icon' => 'voley'],
            ['name' => 'Básquet', 'icon' => 'basket'],
            ['name' => 'Tenis', 'icon' => 'tenis'],
            ['name' => 'Futsal', 'icon' => 'futbol'], // Futsal usa balón similar, o podrías usar uno específico
            ['name' => 'Frontón', 'icon' => 'fronton'],
            ['name' => 'Ping Pong', 'icon' => 'pingpong'],
            ['name' => 'Rugby', 'icon' => 'rugby'],
            ['name' => 'Béisbol', 'icon' => 'baseball'],
        ];

        foreach ($sports as $sport) {
            Sport::firstOrCreate(
                ['name' => $sport['name']],
                ['icon' => $sport['icon']] // Guarda la CLAVE del icono (ej: 'futbol')
            );
        }
    }
}