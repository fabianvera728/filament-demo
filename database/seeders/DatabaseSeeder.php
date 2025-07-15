<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // Primero los datos básicos
            UserSeeder::class,
            ZoneSeeder::class,
            FranchiseSeeder::class,
            
            // Luego categorías y productos
            CategorySeeder::class,
            ProductSeeder::class,
            
            // Campañas y días festivos
            CampaignSeeder::class,
            HolidaySeeder::class,
            
            // Pedidos y elementos relacionados
            OrderSeeder::class,
            DeliverySeeder::class,
            PaymentSeeder::class,
            
            // Sistema de atención al cliente y bonos
            PqrSeeder::class,
            BonusSeeder::class,
        ]);
    }
}
