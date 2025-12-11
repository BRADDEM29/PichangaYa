<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\District;

class DistrictSeeder extends Seeder
{
    public function run(): void
    {
        $districts = [
            'Cusco',
            'Wanchaq',
            'San Sebastián',
            'San Jerónimo',
            'Santiago',
            'Poroy',
            'Saylla',
            'Wimpillay'
        ];

        foreach ($districts as $district) {
            District::firstOrCreate(['name' => $district]);
        }
    }
}