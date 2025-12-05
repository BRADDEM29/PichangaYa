<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cancha;
use App\Models\User;

class CanchaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Asegurarnos de que existe el usuario dueño (ID 2 según tus seeders anteriores)
        // O usamos el primero que encontremos
        $owner = User::where('role', 'owner')->first() ?? User::find(1);

        // --- CANCHA 1 ---
        $cancha1 = Cancha::create([
            'name'           => 'Canchas El Golazo',
            'address'        => 'Av. Cultura 100',
            'price_per_hour' => 50.00,
            'description'    => 'Cancha sintética techada con iluminación LED.',
            'district_id'    => 2, // Wanchaq (Asegúrate que exista el distrito 2)
            'user_id'        => $owner->id,
            'open_time'      => '08:00:00',
            'close_time'     => '23:00:00',
            'contact_phone'  => '984000111',
            'lat'            => -13.522640,
            'lng'            => -71.967340,
        ]);
        
        // Asignar Deportes (Fútbol)
        // Asumiendo que ID 1 es Fútbol en tu tabla sports
        $cancha1->sports()->attach([1]); 


        // --- CANCHA 2 ---
        $cancha2 = Cancha::create([
            'name'           => 'Complejo Deportivo La Red',
            'address'        => 'Calle Los Incas 450',
            'price_per_hour' => 30.00,
            'description'    => 'Losa deportiva para voley y futsal.',
            'district_id'    => 1, // Cusco
            'user_id'        => $owner->id,
            'open_time'      => '07:00:00',
            'close_time'     => '22:00:00',
            'contact_phone'  => '984000222',
            'lat'            => -13.5167,
            'lng'            => -71.9788,
        ]);

        // Asignar Deportes (Fútbol y Vóley)
        // Asumiendo que ID 1 es Fútbol y ID 2 es Vóley
        $cancha2->sports()->attach([1, 2]); 
    }
}