<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            ['name' => 'Wi-Fi', 'icon' => '📶'],
            ['name' => 'Estacionamiento / Garaje', 'icon' => '🚗'],
            ['name' => 'Vestuario / Duchas', 'icon' => '🚿'],
            ['name' => 'Bar / Restaurante', 'icon' => '🍔'],
            ['name' => 'Pasto Sintético', 'icon' => '🌿'],
            ['name' => 'Pasto Natural', 'icon' => '🌱'],
            ['name' => 'Losa Deportiva', 'icon' => '🧱'],
            ['name' => 'Iluminación LED', 'icon' => '💡'],
            ['name' => 'Gradas / Tribuna', 'icon' => '🏟️'],
            ['name' => 'Música / Parlantes', 'icon' => '🎵'],
            ['name' => 'Organización de Torneos', 'icon' => '🏆'],
            ['name' => 'Cumpleaños / Eventos', 'icon' => '🎂'],
            ['name' => 'Escuela Deportiva', 'icon' => '⚽'],
            ['name' => 'Beelup (Grabación)', 'icon' => '📹'],
        ];

        foreach ($services as $service) {
            Service::firstOrCreate(
                ['name' => $service['name']], // Busca por nombre
                ['icon' => $service['icon']]  // Si no existe, crea con icono
            );
        }
    }
}