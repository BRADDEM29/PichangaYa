<?php
// C:\laragon\www\PichangaYa\pichangaya\database\seeders\ServiceSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            // LISTA SOLICITADA VS CONFIGURACIÓN EXISTENTE
            
            ['name' => 'Wi-Fi',                     'icon' => 'wifi'],
            ['name' => 'Estacionamiento / Garaje',  'icon' => 'estacionamiento'],
            ['name' => 'Vestuario',                 'icon' => 'vestuarios'],
            ['name' => 'Bar',                       'icon' => 'bar'],
            ['name' => 'Pasto Sintético',           'icon' => 'pasto_sintetico'],
            ['name' => 'Pasto Natural',             'icon' => 'pasto_natural'],
            ['name' => 'Losa Deportiva',            'icon' => 'Losa'],
            ['name' => 'Iluminación LED',           'icon' => 'iluminacion'],
            ['name' => 'Gradas / Tribuna',          'icon' => 'tribunas'],
            ['name' => 'Música / Parlantes',        'icon' => 'Parlante'],
            ['name' => 'Organización de Torneos',   'icon' => 'Torneos'],
            ['name' => 'Cumpleaños / Eventos',      'icon' => 'Cumpleaños_Eventos'],
            ['name' => 'Escuela Deportiva',         'icon' => 'Escuela_Deportiva'],
            ['name' => 'Grabación',                 'icon' => 'Grabación'], // Usamos la clave directa 'Grabación' del config
            ['name' => 'Restaurante / Cafetería',   'icon' => 'cafeteria'], // Mapeado a 'cafeteria'
            ['name' => 'Duchas',                    'icon' => 'duchas'],
            ['name' => 'Pagos Con Tarjeta',         'icon' => 'pagos_con_tarjeta'],
            ['name' => 'Casilleros',                'icon' => 'casilleros'],
            ['name' => 'Zona Parrillas',            'icon' => 'zona_parrillas'],
            ['name' => 'Primeros Auxilios',         'icon' => 'primeros_auxilios'],
            ['name' => 'Marcador',                  'icon' => 'marcador'],
            ['name' => 'Video Vigilancia',          'icon' => 'Video_Vigilancia'],
            
            // Extras útiles que tenías en config pero no en la lista (opcionales, los dejo por si acaso)
            ['name' => 'Agua Potable',              'icon' => 'agua_potable'],
            ['name' => 'Arbitraje',                 'icon' => 'arbitraje'],
            ['name' => 'Seguridad',                 'icon' => 'seguridad'],
        ];

        foreach ($services as $service) {
            Service::firstOrCreate(
                ['name' => $service['name']], // Busca por nombre
                ['icon' => $service['icon']]  // Guarda la CLAVE del config
            );
        }
    }
}