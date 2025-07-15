<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Zone;

class ZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $zones = [
            [
                'name' => 'Centro Histórico',
                'code' => 'CTH',
                'description' => 'Zona del centro histórico de Bogotá',
                'delivery_fee' => 5000,
                'min_order_amount' => 20000,
                'estimated_delivery_time' => 30,
                'coordinates' => [
                    ['latitude' => 4.5981, 'longitude' => -74.0758]
                ],
                'coverage_area' => [
                    ['name' => 'La Candelaria', 'description' => 'Barrio histórico'],
                    ['name' => 'Centro', 'description' => 'Zona comercial central'],
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Zona Norte',
                'code' => 'ZNT',
                'description' => 'Zona norte de Bogotá',
                'delivery_fee' => 7000,
                'min_order_amount' => 25000,
                'estimated_delivery_time' => 45,
                'coordinates' => [
                    ['latitude' => 4.7110, 'longitude' => -74.0721]
                ],
                'coverage_area' => [
                    ['name' => 'Usaquén', 'description' => 'Zona residencial norte'],
                    ['name' => 'Chapinero', 'description' => 'Zona comercial y residencial'],
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Zona Sur',
                'code' => 'ZSR',
                'description' => 'Zona sur de Bogotá',
                'delivery_fee' => 6000,
                'min_order_amount' => 18000,
                'estimated_delivery_time' => 40,
                'coordinates' => [
                    ['latitude' => 4.4716, 'longitude' => -74.1094]
                ],
                'coverage_area' => [
                    ['name' => 'Ciudad Bolívar', 'description' => 'Zona residencial sur'],
                    ['name' => 'Bosa', 'description' => 'Zona comercial sur'],
                ],
                'is_active' => true,
            ],
            [
                'name' => 'El Poblado',
                'code' => 'EPD',
                'description' => 'Zona exclusiva de Medellín',
                'delivery_fee' => 8000,
                'min_order_amount' => 30000,
                'estimated_delivery_time' => 25,
                'coordinates' => [
                    ['latitude' => 6.2088, 'longitude' => -75.5648]
                ],
                'coverage_area' => [
                    ['name' => 'El Poblado', 'description' => 'Zona turística y comercial'],
                    ['name' => 'Envigado', 'description' => 'Zona residencial'],
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Centro Medellín',
                'code' => 'CTM',
                'description' => 'Centro de Medellín',
                'delivery_fee' => 5500,
                'min_order_amount' => 22000,
                'estimated_delivery_time' => 35,
                'coordinates' => [
                    ['latitude' => 6.2442, 'longitude' => -75.5812]
                ],
                'coverage_area' => [
                    ['name' => 'La Candelaria', 'description' => 'Centro histórico'],
                    ['name' => 'Boston', 'description' => 'Zona comercial'],
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Granada',
                'code' => 'GRN',
                'description' => 'Zona Granada Cali',
                'delivery_fee' => 6500,
                'min_order_amount' => 25000,
                'estimated_delivery_time' => 30,
                'coordinates' => [
                    ['latitude' => 3.4516, 'longitude' => -76.5320]
                ],
                'coverage_area' => [
                    ['name' => 'Granada', 'description' => 'Zona comercial y gastronómica'],
                    ['name' => 'San Fernando', 'description' => 'Zona residencial'],
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Centro Cali',
                'code' => 'CTC',
                'description' => 'Centro de Cali',
                'delivery_fee' => 5000,
                'min_order_amount' => 20000,
                'estimated_delivery_time' => 40,
                'coordinates' => [
                    ['latitude' => 3.4372, 'longitude' => -76.5225]
                ],
                'coverage_area' => [
                    ['name' => 'Centro', 'description' => 'Zona comercial central'],
                    ['name' => 'La Merced', 'description' => 'Zona histórica'],
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Zona Rosa',
                'code' => 'ZRS',
                'description' => 'Zona Rosa de Bogotá',
                'delivery_fee' => 7500,
                'min_order_amount' => 28000,
                'estimated_delivery_time' => 35,
                'coordinates' => [
                    ['latitude' => 4.6697, 'longitude' => -74.0628]
                ],
                'coverage_area' => [
                    ['name' => 'Zona Rosa', 'description' => 'Zona de entretenimiento'],
                    ['name' => 'Chapinero Norte', 'description' => 'Zona comercial'],
                ],
                'is_active' => true,
            ],
        ];

        foreach ($zones as $zone) {
            Zone::create($zone);
        }
    }
}
