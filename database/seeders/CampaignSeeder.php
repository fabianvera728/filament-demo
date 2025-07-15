<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Campaign;
use App\Models\Product;
use Illuminate\Support\Str;

class CampaignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $campaigns = [
            [
                'name' => 'Black Friday 2024',
                'description' => 'Descuentos especiales del Black Friday en toda la plataforma',
                'type' => 'discount',
                'discount_type' => 'percentage',
                'discount_value' => 25.0,
                'minimum_order_amount' => 50000,
                'maximum_discount_amount' => 30000,
                'usage_limit' => 1000,
                'usage_limit_per_customer' => 1,
                'starts_at' => now()->addDays(10),
                'ends_at' => now()->addDays(13),
                'is_active' => true,
                'code' => 'BLACKFRIDAY2024',
                'is_public' => true,
                'priority' => 'high',
                'requires_code' => true,
            ],
            [
                'name' => 'Descuento Bienvenida',
                'description' => 'Descuento especial para nuevos usuarios',
                'type' => 'discount',
                'discount_type' => 'fixed_amount',
                'discount_value' => 10000,
                'minimum_order_amount' => 30000,
                'maximum_discount_amount' => 10000,
                'usage_limit' => null,
                'usage_limit_per_customer' => 1,
                'starts_at' => now()->subDays(30),
                'ends_at' => now()->addDays(60),
                'is_active' => true,
                'code' => 'BIENVENIDO10',
                'is_public' => true,
                'priority' => 'normal',
                'requires_code' => true,
            ],
            [
                'name' => 'Happy Hour Pizzas',
                'description' => '20% de descuento en pizzas de 3:00 PM a 6:00 PM',
                'type' => 'discount',
                'discount_type' => 'percentage',
                'discount_value' => 20.0,
                'minimum_order_amount' => 25000,
                'maximum_discount_amount' => 15000,
                'usage_limit' => 200,
                'usage_limit_per_customer' => 1,
                'starts_at' => now()->subDays(7),
                'ends_at' => now()->addDays(30),
                'is_active' => true,
                'code' => 'HAPPYPIZZA',
                'is_public' => true,
                'priority' => 'high',
                'requires_code' => true,
            ],
            [
                'name' => 'Combo Familiar',
                'description' => 'Descuento especial para pedidos familiares',
                'type' => 'discount',
                'discount_type' => 'percentage',
                'discount_value' => 15.0,
                'minimum_order_amount' => 80000,
                'maximum_discount_amount' => 20000,
                'usage_limit' => 500,
                'usage_limit_per_customer' => 3,
                'starts_at' => now()->subDays(5),
                'ends_at' => now()->addDays(45),
                'is_active' => true,
                'code' => 'FAMILIAR15',
                'is_public' => true,
                'priority' => 'normal',
                'requires_code' => true,
            ],
            [
                'name' => 'Descuento VIP',
                'description' => 'Descuento exclusivo para clientes VIP',
                'type' => 'discount',
                'discount_type' => 'percentage',
                'discount_value' => 30.0,
                'minimum_order_amount' => 100000,
                'maximum_discount_amount' => 50000,
                'usage_limit' => 50,
                'usage_limit_per_customer' => 5,
                'starts_at' => now()->subDays(15),
                'ends_at' => now()->addDays(75),
                'is_active' => true,
                'code' => 'VIP30',
                'is_public' => false,
                'priority' => 'high',
                'requires_code' => true,
            ],
            [
                'name' => 'Fin de Semana Sushi',
                'description' => 'Promoción especial de sushi los fines de semana',
                'type' => 'discount',
                'discount_type' => 'fixed_amount',
                'discount_value' => 8000,
                'minimum_order_amount' => 40000,
                'maximum_discount_amount' => 8000,
                'usage_limit' => 300,
                'usage_limit_per_customer' => 2,
                'starts_at' => now()->subDays(2),
                'ends_at' => now()->addDays(90),
                'is_active' => true,
                'code' => 'SUSHIWEEKEND',
                'is_public' => true,
                'priority' => 'normal',
                'requires_code' => true,
            ],
            [
                'name' => 'Promoción Estudiantes',
                'description' => 'Descuento especial para estudiantes con carnet',
                'type' => 'discount',
                'discount_type' => 'percentage',
                'discount_value' => 12.0,
                'minimum_order_amount' => 15000,
                'maximum_discount_amount' => 8000,
                'usage_limit' => 1000,
                'usage_limit_per_customer' => 10,
                'starts_at' => now()->subDays(20),
                'ends_at' => now()->addDays(120),
                'is_active' => true,
                'code' => 'ESTUDIANTE12',
                'is_public' => true,
                'priority' => 'low',
                'requires_code' => true,
            ],
            [
                'name' => 'Envío Gratis',
                'description' => 'Envío gratuito en pedidos superiores a $35,000',
                'type' => 'discount',
                'discount_type' => 'free_shipping',
                'discount_value' => 0,
                'minimum_order_amount' => 35000,
                'maximum_discount_amount' => null,
                'usage_limit' => null,
                'usage_limit_per_customer' => null,
                'starts_at' => now()->subDays(10),
                'ends_at' => now()->addDays(60),
                'is_active' => true,
                'code' => 'ENVIOGRATIS',
                'is_public' => true,
                'priority' => 'normal',
                'requires_code' => false,
            ],
        ];

        $createdCampaigns = [];
        foreach ($campaigns as $campaignData) {
            $campaign = Campaign::create($campaignData);
            $createdCampaigns[] = $campaign;
        }

        // Asociar productos a algunas campañas específicas
        $products = Product::all();

        // Happy Hour Pizzas - asociar solo pizzas
        $pizzaProducts = $products->filter(function ($product) {
            return stripos($product->name, 'pizza') !== false;
        });
        
        if ($pizzaProducts->count() > 0) {
            $happyHourCampaign = $createdCampaigns[2]; // Happy Hour Pizzas
            $happyHourCampaign->products()->attach($pizzaProducts->pluck('id')->toArray());
        }

        // Fin de Semana Sushi - asociar solo sushi
        $sushiProducts = $products->filter(function ($product) {
            return stripos($product->name, 'sushi') !== false || 
                   stripos($product->name, 'roll') !== false ||
                   stripos($product->name, 'sashimi') !== false ||
                   stripos($product->name, 'chirashi') !== false;
        });
        
        if ($sushiProducts->count() > 0) {
            $sushiCampaign = $createdCampaigns[5]; // Fin de Semana Sushi
            $sushiCampaign->products()->attach($sushiProducts->pluck('id')->toArray());
        }

        // Black Friday - productos aleatorios (50% de todos los productos)
        $blackFridayCampaign = $createdCampaigns[0];
        $randomProducts = $products->random(min($products->count(), 30));
        $blackFridayCampaign->products()->attach($randomProducts->pluck('id')->toArray());

        // Combo Familiar - productos de mayor precio
        $expensiveProducts = $products->where('price', '>=', 25000);
        if ($expensiveProducts->count() > 0) {
            $familyCampaign = $createdCampaigns[3]; // Combo Familiar
            $familyCampaign->products()->attach($expensiveProducts->pluck('id')->toArray());
        }
    }
}
