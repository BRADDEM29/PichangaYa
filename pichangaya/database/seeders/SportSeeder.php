<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sport;

class SportSeeder extends Seeder
{
    public function run(): void
    {
        // 🟢 DEFINICIÓN DE DEPORTES Y SUS JUGADORES TOTALES
        $sports = [
            ['name' => 'Fútbol 5',  'total_players' => 10, 'icon' => 'futbol'], // 5 vs 5
            ['name' => 'Fútbol 7',  'total_players' => 14, 'icon' => 'futbol'], // 7 vs 7
            ['name' => 'Fútbol 11', 'total_players' => 22, 'icon' => 'futbol'], // 11 vs 11
            ['name' => 'Vóley',     'total_players' => 12, 'icon' => 'voley'],  // 6 vs 6
            ['name' => 'Básquet',   'total_players' => 10, 'icon' => 'basket'], // 5 vs 5
            ['name' => 'Tenis',     'total_players' => 2,  'icon' => 'tenis'],  // 1 vs 1
            ['name' => 'Futsal',    'total_players' => 10, 'icon' => 'futbol'], // 5 vs 5
            ['name' => 'Frontón',   'total_players' => 2,  'icon' => 'fronton'],// 1 vs 1
            ['name' => 'Ping Pong', 'total_players' => 2,  'icon' => 'pingpong'],// 1 vs 1
            ['name' => 'Rugby',     'total_players' => 30, 'icon' => 'rugby'],  // 15 vs 15
            ['name' => 'Béisbol',   'total_players' => 18, 'icon' => 'baseball'],// 9 vs 9
        ];

        foreach ($sports as $sport) {
            Sport::updateOrCreate(
                ['name' => $sport['name']], // Busca por nombre
                [
                    'total_players' => $sport['total_players'], // Actualiza o crea la cantidad
                    'icon' => $sport['icon']
                ]
            );
        }
    }
}