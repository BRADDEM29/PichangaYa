<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cancha;

class CanchaSeeder extends Seeder
{
    public function run(): void
    {
        // Cancha 1: Fútbol en Wanchaq
        Cancha::create([
            'name' => 'Canchas El Golazo',
            'address' => 'Av. Cultura 100',
            'price_per_hour' => 50.00,
            'description' => 'Cancha sintética techada con iluminación LED.',
            'district_id' => 2, // Wanchaq
            'sport_id' => 1,    // Fútbol
            'user_id' => 1,     // Admin
        ]);

        // Cancha 2: Vóley en Cusco
        Cancha::create([
            'name' => 'Complejo Deportivo Cusco',
            'address' => 'Calle Saphy 45',
            'price_per_hour' => 30.00,
            'description' => 'Losa deportiva multiusos.',
            'district_id' => 1, // Cusco
            'sport_id' => 4,    // Vóley
            'user_id' => 1,
        ]);
    }
}