<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Business;

class BusinessSeeder extends Seeder
{
    public function run(): void
    {
        // Negocio 1 (Fútbol)
        Business::create([
            'name' => 'Canchas El Golazo',
            'address' => 'Av. Cultura 100',
            'district_id' => 2, 
            'sport_id' => 1, // <--- FÚTBOL
            'user_id' => 1,
        ]);

        // Negocio 2 (Vóley)
        Business::create([
            'name' => 'Complejo Deportivo Cusco',
            'address' => 'Calle Saphy 45',
            'district_id' => 1, 
            'sport_id' => 4, // <--- VÓLEY
            'user_id' => 1,
        ]);
        
        // Negocio 3 (Fútbol)
        Business::create([
            'name' => 'El Monumental del Sur',
            'address' => 'Vía Expresa Km 5',
            'district_id' => 3, 
            'sport_id' => 1, // <--- FÚTBOL
            'user_id' => 1,
        ]);
    }
}