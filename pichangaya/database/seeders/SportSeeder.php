<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sport;

class SportSeeder extends Seeder
{
    public function run(): void
    {
        $sports = [
            ['name' => 'Fútbol 5', 'icon' => '⚽'],
            ['name' => 'Fútbol 7', 'icon' => '⚽'],
            ['name' => 'Fútbol 11', 'icon' => '🏟️'],
            ['name' => 'Vóley', 'icon' => '🏐'],
            ['name' => 'Básquet', 'icon' => '🏀'],
            ['name' => 'Tenis', 'icon' => '🎾'],
            ['name' => 'Futsal', 'icon' => '👟'],
        ];

        foreach ($sports as $sport) {
            Sport::create($sport);
        }
    }
}