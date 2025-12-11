<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cancha;
use App\Models\User;
use App\Models\District;
use App\Models\Sport;
use App\Models\Service;

class CanchaSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Obtener Dueño
        $owner = User::where('role', 'owner')->first();
        if (!$owner) {
            $owner = User::create([
                'name' => 'Juan Dueño',
                'email' => 'dueno@yanakatari.com',
                'password' => bcrypt('password'),
                'role' => 'owner'
            ]);
        }

        // 2. Obtener IDs auxiliares (Para no usar números fijos y evitar errores)
        $wanchaq = District::where('name', 'Wanchaq')->first()->id ?? 1;
        $cusco   = District::where('name', 'Cusco')->first()->id ?? 1;

        // Deportes
        $futbol7 = Sport::where('name', 'like', '%Fútbol 7%')->first();
        $futbol5 = Sport::where('name', 'like', '%Fútbol 5%')->first();
        $voley   = Sport::where('name', 'like', '%Vóley%')->first();

        // Servicios
        $wifi    = Service::where('name', 'like', '%Wi-Fi%')->first();
        $duchas  = Service::where('name', 'like', '%Duchas%')->first();
        $parking = Service::where('name', 'like', '%Estacionamiento%')->first();
        $led     = Service::where('name', 'like', '%LED%')->first();
        $beelup  = Service::where('name', 'like', '%Beelup%')->first();

        // --- CANCHA 1 (DESTACADA) ---
        $cancha1 = Cancha::create([
            'name'           => 'Canchas El Golazo',
            'address'        => 'Av. Cultura 100',
            'price_per_hour' => 50.00,
            'description'    => 'La mejor cancha sintética de la ciudad. Techada, con iluminación LED profesional y sistema de grabación de jugadas.',
            'district_id'    => $wanchaq,
            'user_id'        => $owner->id,
            'open_time'      => '08:00:00',
            'close_time'     => '23:00:00',
            'contact_phone'  => '984000111',
            'lat'            => -13.522640,
            'lng'            => -71.967340,
            'is_featured'    => true, // 🟢 IMPORTANTE: Aparecerá en el carrusel
        ]);
        
        // Asignar Deportes
        if ($futbol7) $cancha1->sports()->attach([$futbol7->id]);
        
        // Asignar Servicios (Aquí llenamos los requerimientos automáticamente)
        $serviciosCancha1 = collect([$wifi, $parking, $led, $beelup])->filter()->pluck('id');
        $cancha1->services()->sync($serviciosCancha1);


        // --- CANCHA 2 (NORMAL) ---
        $cancha2 = Cancha::create([
            'name'           => 'Complejo Deportivo La Red',
            'address'        => 'Calle Los Incas 450',
            'price_per_hour' => 30.00,
            'description'    => 'Losa deportiva multifuncional ideal para voley y futsal rápido. Ambiente familiar.',
            'district_id'    => $cusco,
            'user_id'        => $owner->id,
            'open_time'      => '07:00:00',
            'close_time'     => '22:00:00',
            'contact_phone'  => '984000222',
            'lat'            => -13.5167,
            'lng'            => -71.9788,
            'is_featured'    => false,
        ]);

        // Asignar Deportes (Multideporte)
        $deportesCancha2 = collect([$futbol5, $voley])->filter()->pluck('id');
        $cancha2->sports()->sync($deportesCancha2);

        // Asignar Servicios
        $serviciosCancha2 = collect([$duchas, $parking])->filter()->pluck('id');
        $cancha2->services()->sync($serviciosCancha2);
    }
}