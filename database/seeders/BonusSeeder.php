<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Bonus;
use App\Models\User;
use App\Models\Order;

class BonusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clients = User::where('role', 'client')->get();
        $orders = Order::where('payment_status', 'paid')->get();

        if ($clients->isEmpty()) {
            return;
        }

        foreach ($clients as $client) {
            // Bono de bienvenida para todos los clientes
            Bonus::create([
                'user_id' => $client->id,
                'type' => 'discount',
                'source' => 'manual',
                'status' => rand(0, 3) === 0 ? 'used' : 'active', // 25% ya usados
                'value' => 5000,
                'used_value' => 0,
                'remaining_value' => 5000,
                'currency' => 'COP',
                'source_order_id' => null,
                'source_user_id' => null,
                'minimum_order_amount' => 20000,
                'earned_at' => $client->created_at,
                'expires_at' => now()->addDays(90),
                'used_at' => rand(0, 3) === 0 ? now()->subDays(rand(1, 30)) : null,
                'title' => 'Bono de Bienvenida',
                'description' => 'Bono de bienvenida por registrarse en Domisoft',
                'is_transferable' => false,
                'is_combinable' => true,
                'created_at' => $client->created_at,
                'updated_at' => $client->created_at,
            ]);

            // Bonos por cumpleaños (si es su mes de cumpleaños)
            if ($client->birth_date && $client->birth_date->month === now()->month) {
                Bonus::create([
                    'user_id' => $client->id,
                    'type' => 'discount',
                    'source' => 'birthday',
                    'status' => 'active',
                    'value' => 15000,
                    'used_value' => 0,
                    'remaining_value' => 15000,
                    'currency' => 'COP',
                    'source_order_id' => null,
                    'source_user_id' => null,
                    'minimum_order_amount' => 30000,
                    'earned_at' => now()->subDays(rand(1, 15)),
                    'expires_at' => now()->addDays(30),
                    'used_at' => null,
                    'title' => 'Bono de Cumpleaños',
                    'description' => 'Bono especial de cumpleaños - ¡Feliz cumpleaños!',
                    'is_transferable' => false,
                    'is_combinable' => true,
                    'created_at' => now()->subDays(rand(1, 15)),
                    'updated_at' => now()->subDays(rand(1, 15)),
                ]);
            }

            // Bonos de fidelidad para clientes frecuentes
            $clientOrders = $orders->where('user_id', $client->id);
            if ($clientOrders->count() >= 5) {
                Bonus::create([
                    'user_id' => $client->id,
                    'type' => 'points',
                    'source' => 'loyalty',
                    'status' => rand(0, 2) === 0 ? 'used' : 'active',
                    'value' => 8000,
                    'used_value' => 0,
                    'remaining_value' => 8000,
                    'currency' => 'COP',
                    'source_order_id' => null,
                    'source_user_id' => null,
                    'minimum_order_amount' => 25000,
                    'earned_at' => now()->subDays(rand(5, 20)),
                    'expires_at' => now()->addDays(60),
                    'used_at' => rand(0, 2) === 0 ? now()->subDays(rand(1, 20)) : null,
                    'title' => 'Bono de Fidelidad',
                    'description' => 'Bono de fidelidad por ser cliente frecuente',
                    'is_transferable' => false,
                    'is_combinable' => true,
                    'created_at' => now()->subDays(rand(5, 20)),
                    'updated_at' => now()->subDays(rand(1, 15)),
                ]);
            }

            // Bonos por referencia (algunos clientes)
            if (rand(0, 4) === 0) { // 20% de clientes tienen bonos por referencia
                Bonus::create([
                    'user_id' => $client->id,
                    'type' => 'cashback',
                    'source' => 'referral',
                    'status' => rand(0, 1) ? 'used' : 'active',
                    'value' => 10000,
                    'used_value' => 0,
                    'remaining_value' => 10000,
                    'currency' => 'COP',
                    'source_order_id' => null,
                    'source_user_id' => $clients->random()->id,
                    'minimum_order_amount' => 15000,
                    'earned_at' => now()->subDays(rand(10, 25)),
                    'expires_at' => now()->addDays(45),
                    'used_at' => rand(0, 1) ? now()->subDays(rand(1, 25)) : null,
                    'title' => 'Bono por Referencia',
                    'description' => 'Bono por referir un amigo que realizó su primera compra',
                    'is_transferable' => false,
                    'is_combinable' => true,
                    'created_at' => now()->subDays(rand(10, 25)),
                    'updated_at' => now()->subDays(rand(1, 20)),
                ]);
            }

            // Bonos por reseñas (algunos clientes)
            if (rand(0, 2) === 0) { // 33% de clientes escriben reseñas
                for ($i = 0; $i < rand(1, 3); $i++) {
                    Bonus::create([
                        'user_id' => $client->id,
                        'type' => 'points',
                        'source' => 'order',
                        'status' => rand(0, 3) === 0 ? 'used' : 'active',
                        'value' => 2000,
                        'used_value' => 0,
                        'remaining_value' => 2000,
                        'currency' => 'COP',
                        'source_order_id' => $clientOrders->isNotEmpty() ? $clientOrders->random()->id : null,
                        'source_user_id' => null,
                        'minimum_order_amount' => 10000,
                        'earned_at' => now()->subDays(rand(1, 30)),
                        'expires_at' => now()->addDays(30),
                        'used_at' => rand(0, 3) === 0 ? now()->subDays(rand(1, 15)) : null,
                        'title' => 'Bono por Reseña',
                        'description' => 'Bono por escribir una reseña detallada',
                        'is_transferable' => false,
                        'is_combinable' => true,
                        'created_at' => now()->subDays(rand(1, 30)),
                        'updated_at' => now()->subDays(rand(1, 25)),
                    ]);
                }
            }
        }

        // Bonos por compras (basados en órdenes existentes)
        foreach ($orders->take(15) as $order) { // Solo para las primeras 15 órdenes para no sobrecargar
            $purchaseBonus = floor($order->total_amount * 0.01); // 1% del total como puntos
            
            if ($purchaseBonus >= 100) { // Solo si el bono es significativo
                Bonus::create([
                    'user_id' => $order->user_id,
                    'type' => 'points',
                    'source' => 'order',
                    'status' => rand(0, 4) === 0 ? 'used' : 'active', // 20% ya usados
                    'value' => $purchaseBonus,
                    'used_value' => 0,
                    'remaining_value' => $purchaseBonus,
                    'currency' => 'COP',
                    'source_order_id' => $order->id,
                    'source_user_id' => null,
                    'minimum_order_amount' => 0,
                    'earned_at' => $order->created_at->addHours(1),
                    'expires_at' => now()->addDays(120),
                    'used_at' => rand(0, 4) === 0 ? now()->subDays(rand(1, 30)) : null,
                    'title' => 'Puntos por Compra',
                    'description' => "Puntos por compra - Pedido #{$order->order_number}",
                    'is_transferable' => false,
                    'is_combinable' => true,
                    'created_at' => $order->created_at->addHours(1),
                    'updated_at' => $order->updated_at,
                ]);
            }
        }
    }
}
