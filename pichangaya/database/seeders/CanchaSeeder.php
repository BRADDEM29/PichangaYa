<?php
// C:\laragon\www\PichangaYa\pichangaya\database\seeders\CanchaSeeder.php

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
        // 1. Obtener o Crear Dueño
        $owner = User::firstOrCreate(
            ['email' => 'dueno@yanakatari.com'],
            [
                'name' => 'Juan Dueño',
                'password' => bcrypt('password'),
                'role' => 'owner'
            ]
        );

        // 2. Obtener Distritos (con fallback seguro)
        $wanchaq = District::firstOrCreate(['name' => 'Wanchaq'])->id;
        $cusco   = District::firstOrCreate(['name' => 'Cusco'])->id;

        // 3. Obtener Deportes (Busqueda exacta para evitar errores)
        // Usamos los nombres que definimos en SportSeeder
        $futbol7 = Sport::where('name', 'Fútbol 7')->first();
        $futbol5 = Sport::where('name', 'Fútbol 5')->first();
        $voley   = Sport::where('name', 'Vóley')->first();

        // 4. Obtener Servicios (Busqueda exacta)
        // Usamos los nombres que definimos en ServiceSeeder
        $wifi    = Service::where('name', 'Wi-Fi')->first();
        $duchas  = Service::where('name', 'Duchas')->first(); // O 'Vestuario / Duchas' según tu seeder
        $parking = Service::where('name', 'Estacionamiento / Garaje')->first();
        $led     = Service::where('name', 'Iluminación LED')->first();
        $beelup  = Service::where('name', 'Beelup (Grabación)')->first();

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
            'is_featured'    => true, // Aparecerá en el carrusel
        ]);
        
        // Asignar Deportes (Verificamos que existan)
        if ($futbol7) $cancha1->sports()->attach([$futbol7->id]);
        
        // Asignar Servicios
        // Creamos una colección y filtramos los nulos por si algún servicio no se encontró
        $serviciosCancha1 = collect([$wifi, $parking, $led, $beelup])->filter()->pluck('id');
        if ($serviciosCancha1->isNotEmpty()) {
            $cancha1->services()->sync($serviciosCancha1);
        }


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

        // Asignar Deportes
        $deportesCancha2 = collect([$futbol5, $voley])->filter()->pluck('id');
        if ($deportesCancha2->isNotEmpty()) {
            $cancha2->sports()->sync($deportesCancha2);
        }

        // Asignar Servicios
        $serviciosCancha2 = collect([$duchas, $parking])->filter()->pluck('id');
        if ($serviciosCancha2->isNotEmpty()) {
            $cancha2->services()->sync($serviciosCancha2);
        }
    }
}