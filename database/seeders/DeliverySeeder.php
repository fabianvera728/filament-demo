<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Delivery;
use App\Models\Order;
use App\Models\User;

class DeliverySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders = Order::whereIn('status', ['confirmed', 'preparing', 'ready', 'dispatched', 'delivered'])->get();
        $deliveryPersons = User::where('role', 'delivery')->get();

        if ($orders->isEmpty() || $deliveryPersons->isEmpty()) {
            return;
        }

        foreach ($orders as $order) {
            $deliveryPerson = $deliveryPersons->random();
            
            $status = $this->getDeliveryStatus($order->status);
            $assignedAt = $order->created_at->addMinutes(rand(10, 60));
            
            $delivery = Delivery::create([
                'delivery_number' => 'DEL-' . str_pad($order->id, 6, '0', STR_PAD_LEFT),
                'order_id' => $order->id,
                'delivery_person_id' => $deliveryPerson->id,
                'zone_id' => $order->zone_id,
                'status' => $status,
                'delivery_type' => rand(0, 10) > 8 ? 'express' : 'standard',
                'delivery_address' => $order->delivery_address,
                'delivery_city' => $order->delivery_city,
                'delivery_state' => 'Bogotá D.C.',
                'delivery_latitude' => $this->generateRandomLatitude(),
                'delivery_longitude' => $this->generateRandomLongitude(),
                'delivery_instructions' => $order->delivery_instructions,
                'recipient_name' => $order->customer_name,
                'recipient_phone' => $order->customer_phone,
                'recipient_email' => $order->customer_email,
                'assigned_at' => $assignedAt,
                'picked_up_at' => $status !== 'assigned' ? $assignedAt->addMinutes(rand(5, 20)) : null,
                'in_transit_at' => in_array($status, ['in_transit', 'delivered']) ? $assignedAt->addMinutes(rand(25, 45)) : null,
                'delivered_at' => $status === 'delivered' ? $assignedAt->addMinutes(rand(50, 120)) : null,
                'estimated_delivery_time' => $order->estimated_delivery_time,
                'delivery_fee' => $order->shipping_amount,
                'tip_amount' => rand(0, 5) === 0 ? rand(1000, 5000) : 0,
                'distance_km' => rand(2, 15),
                'estimated_duration_minutes' => rand(30, 90),
                'delivery_notes' => rand(0, 3) === 0 ? $this->generateDeliveryNotes() : null,
                'created_at' => $assignedAt,
                'updated_at' => $assignedAt->addMinutes(rand(1, 180)),
            ]);

            // Crear eventos de seguimiento
            $this->createTrackingEvents($delivery);
        }
    }

    private function getDeliveryStatus($orderStatus): string
    {
        return match($orderStatus) {
            'confirmed', 'preparing' => 'assigned',
            'ready' => rand(0, 1) ? 'assigned' : 'picked_up',
            'dispatched' => 'in_transit',
            'delivered' => 'delivered',
            default => 'assigned'
        };
    }

    private function generateRandomLatitude(): float
    {
        // Coordenadas aproximadas de Colombia
        return rand(408000, 671000) / 100000; // Entre 4.08 y 6.71
    }

    private function generateRandomLongitude(): float
    {
        // Coordenadas aproximadas de Colombia
        return -rand(7400000, 7700000) / 100000; // Entre -74.0 y -77.0
    }

    private function generateDeliveryNotes(): string
    {
        $notes = [
            'Cliente no estaba, entregado a vecino',
            'Entrega exitosa en recepción',
            'Dificultad para encontrar la dirección',
            'Cliente muy amable',
            'Edificio sin ascensor',
            'Zona de difícil acceso',
            'Entrega rápida sin inconvenientes',
            'Cliente pidió llamar antes de llegar',
            'Portero recibió el pedido',
            'Dirección confusa, se tardó en encontrar',
        ];

        return $notes[array_rand($notes)];
    }

    private function createTrackingEvents(Delivery $delivery): void
    {
        $events = [
            [
                'event_type' => 'status_change',
                'status' => 'assigned',
                'description' => 'Pedido asignado al repartidor',
                'event_time' => $delivery->assigned_at,
            ]
        ];

        if ($delivery->picked_up_at) {
            $events[] = [
                'event_type' => 'status_change',
                'status' => 'picked_up',
                'description' => 'Pedido recogido de la franquicia',
                'event_time' => $delivery->picked_up_at,
            ];
        }

        if ($delivery->in_transit_at) {
            $events[] = [
                'event_type' => 'status_change',
                'status' => 'in_transit',
                'description' => 'En camino al destino',
                'event_time' => $delivery->in_transit_at,
            ];
        }

        if ($delivery->delivered_at) {
            $events[] = [
                'event_type' => 'status_change',
                'status' => 'delivered',
                'description' => 'Pedido entregado exitosamente',
                'event_time' => $delivery->delivered_at,
            ];
        }

        foreach ($events as $eventData) {
            \App\Models\DeliveryTrackingEvent::create([
                'delivery_id' => $delivery->id,
                'event_type' => $eventData['event_type'],
                'status' => $eventData['status'],
                'description' => $eventData['description'],
                'latitude' => rand(0, 1) ? $this->generateRandomLatitude() : null,
                'longitude' => rand(0, 1) ? $this->generateRandomLongitude() : null,
                'event_time' => $eventData['event_time'],
                'created_at' => $eventData['event_time'],
                'updated_at' => $eventData['event_time'],
            ]);
        }
    }
}
