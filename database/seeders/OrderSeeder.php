<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Product;
use App\Models\Zone;
use App\Models\Franchise;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clients = User::where('role', 'client')->get();
        $products = Product::all();
        $zones = Zone::all();
        $franchises = Franchise::all();

        if ($clients->isEmpty() || $products->isEmpty()) {
            return;
        }

        $statuses = ['pending', 'confirmed', 'preparing', 'ready', 'dispatched', 'delivered', 'cancelled'];
        $paymentStatuses = ['pending', 'paid', 'failed', 'refunded'];

        for ($i = 1; $i <= 25; $i++) {
            $client = $clients->random();
            $zone = $zones->random();
            $franchise = $franchises->random();
            
            $status = $statuses[array_rand($statuses)];
            $paymentStatus = $paymentStatuses[array_rand($paymentStatuses)];
            
            // Ajustar payment_status según el status
            if ($status === 'cancelled') {
                $paymentStatus = rand(0, 1) ? 'failed' : 'refunded';
            } elseif (in_array($status, ['confirmed', 'preparing', 'ready', 'dispatched', 'delivered'])) {
                $paymentStatus = 'paid';
            }

            $createdDate = now()->subDays(rand(0, 30))->subHours(rand(0, 23))->subMinutes(rand(0, 59));
            
            $order = Order::create([
                'order_number' => 'DOM-' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'user_id' => $client->id,
                'zone_id' => $zone->id,
                'franchise_id' => $franchise->id,
                'status' => $status,
                'payment_status' => $paymentStatus,
                'subtotal' => 0, // Se calculará después
                'tax_amount' => 0,
                'shipping_amount' => $zone->delivery_fee ?? 5000,
                'discount_amount' => rand(0, 5000),
                'total_amount' => 0, // Se calculará después
                'currency' => 'COP',
                'payment_method' => ['credit_card', 'debit_card', 'cash', 'digital_wallet'][array_rand(['credit_card', 'debit_card', 'cash', 'digital_wallet'])],
                'customer_name' => $client->name,
                'customer_email' => $client->email,
                'customer_phone' => $client->phone,
                'delivery_address' => $this->generateAddress(),
                'delivery_city' => 'Bogotá',
                'delivery_country' => 'Colombia',
                'delivery_instructions' => $this->generateDeliveryInstructions(),
                'estimated_delivery_time' => $createdDate->addHours(rand(1, 3)),
                'customer_notes' => rand(0, 3) === 0 ? $this->generateNotes() : null,
                'delivery_method' => rand(0, 10) > 1 ? 'delivery' : 'pickup',
                'created_at' => $createdDate,
                'updated_at' => $createdDate->addMinutes(rand(1, 120)),
            ]);

            // Agregar items al pedido
            $numberOfItems = rand(1, 5);
            $subtotal = 0;

            for ($j = 0; $j < $numberOfItems; $j++) {
                $product = $products->random();
                $quantity = rand(1, 3);
                $price = $product->price;
                $total = $price * $quantity;
                $subtotal += $total;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'quantity' => $quantity,
                    'unit_price' => $price,
                    'total_price' => $total,
                    'product_options' => $this->generateProductOptions($product->name),
                ]);
            }

            // Calcular totales
            $taxAmount = $subtotal * 0.19; // IVA 19%
            $totalAmount = $subtotal + $taxAmount + $order->shipping_amount - $order->discount_amount;

            $order->update([
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
            ]);

            // Actualizar timestamps según el status
            $this->updateOrderTimestamps($order, $status, $createdDate);
        }
    }

    private function updateOrderTimestamps(Order $order, string $status, $createdDate): void
    {
        $timestamps = [];
        
        if (in_array($status, ['confirmed', 'preparing', 'ready', 'dispatched', 'delivered'])) {
            $timestamps['confirmed_at'] = $createdDate->addMinutes(rand(5, 30));
        }
        
        if (in_array($status, ['preparing', 'ready', 'dispatched', 'delivered'])) {
            $timestamps['prepared_at'] = $createdDate->addMinutes(rand(35, 60));
        }
        
        if (in_array($status, ['dispatched', 'delivered'])) {
            $timestamps['dispatched_at'] = $createdDate->addMinutes(rand(65, 90));
        }
        
        if ($status === 'delivered') {
            $timestamps['delivered_at'] = $createdDate->addMinutes(rand(95, 180));
        }
        
        if ($status === 'cancelled') {
            $timestamps['cancelled_at'] = $createdDate->addMinutes(rand(10, 120));
            $timestamps['cancellation_reason'] = $this->generateCancellationReason();
        }

        if (!empty($timestamps)) {
            $order->update($timestamps);
        }
    }

    private function generateAddress(): string
    {
        $streets = [
            'Calle 85 #15-32, Apartamento 301',
            'Carrera 43A #20-15, Casa 12',
            'Avenida 68 #45-67, Torre B, Piso 8',
            'Calle 127 #52-34, Conjunto Residencial Los Pinos',
            'Carrera 15 #85-21, Edificio Central, Oficina 502',
            'Calle 63 #11-45, Barrio El Rosario',
            'Avenida Boyacá #72-18, Conjunto Villa Nueva',
            'Calle 100 #19-87, Apartamento 1203',
            'Carrera 30 #45-12, Casa Esquinera',
            'Calle 53 #23-67, Edificio Plaza Mayor, Piso 4',
        ];

        return $streets[array_rand($streets)];
    }

    private function generateDeliveryInstructions(): string
    {
        $instructions = [
            'Tocar el timbre del apartamento 301',
            'Llamar al llegar, no hay portero',
            'Entregar en la recepción del edificio',
            'Casa de color azul con portón blanco',
            'Subir hasta el segundo piso',
            'Dejar en la puerta principal si no hay nadie',
            'Preguntar por María en recepción',
            'Torre B, ascensor hasta el piso 8',
            'Casa al final de la cuadra, lado derecho',
            'Oficina en el primer piso, puerta de vidrio',
        ];

        return $instructions[array_rand($instructions)];
    }

    private function generateNotes(): string
    {
        $notes = [
            'Sin cebolla en la hamburguesa',
            'Extra queso en la pizza',
            'Salsa picante aparte',
            'Sin cilantro en los tacos',
            'Bebida bien fría',
            'Punto medio en la carne',
            'Sin tomate',
            'Aderezo César aparte',
            'Sin pepinillos',
            'Extra bacon',
        ];

        return $notes[array_rand($notes)];
    }

    private function generateCancellationReason(): string
    {
        $reasons = [
            'Cliente canceló el pedido',
            'Producto no disponible',
            'Problema con el pago',
            'Fuera del área de entrega',
            'Cliente no disponible',
            'Error en el pedido',
        ];

        return $reasons[array_rand($reasons)];
    }

    private function generateProductOptions($productName): array
    {
        $options = [];

        if (stripos($productName, 'hamburguesa') !== false) {
            $options = [
                'size' => ['pequeña', 'mediana', 'grande'][array_rand(['pequeña', 'mediana', 'grande'])],
                'extras' => rand(0, 1) ? ['extra queso', 'extra bacon'] : [],
                'without' => rand(0, 1) ? ['sin cebolla'] : [],
            ];
        } elseif (stripos($productName, 'pizza') !== false) {
            $options = [
                'size' => ['personal', 'mediana', 'familiar'][array_rand(['personal', 'mediana', 'familiar'])],
                'crust' => ['delgada', 'gruesa'][array_rand(['delgada', 'gruesa'])],
                'extras' => rand(0, 1) ? ['extra queso', 'orégano'] : [],
            ];
        } elseif (stripos($productName, 'bebida') !== false || stripos($productName, 'jugo') !== false) {
            $options = [
                'size' => ['pequeña', 'mediana', 'grande'][array_rand(['pequeña', 'mediana', 'grande'])],
                'ice' => rand(0, 1) ? 'con hielo' : 'sin hielo',
            ];
        }

        return $options;
    }
}
