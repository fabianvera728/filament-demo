<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pqr;
use App\Models\PqrResponse;
use App\Models\User;
use App\Models\Order;

class PqrSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clients = User::where('role', 'client')->get();
        $staff = User::whereIn('role', ['admin', 'office'])->get();
        $orders = Order::all();

        if ($clients->isEmpty()) {
            return;
        }

        $pqrs = [
            [
                'type' => 'peticion',
                'subject' => 'Solicitud de menú vegetariano',
                'description' => 'Me gustaría solicitar que incluyan más opciones vegetarianas en el menú. Como cliente frecuente, creo que sería beneficioso para muchas personas.',
                'priority' => 'medium',
                'status' => 'resolved',
            ],
            [
                'type' => 'queja',
                'subject' => 'Demora en la entrega',
                'description' => 'Mi pedido se demoró más de 2 horas en llegar y cuando llegó, la comida estaba fría. Esto es inaceptable.',
                'priority' => 'high',
                'status' => 'resolved',
            ],
            [
                'type' => 'reclamo',
                'subject' => 'Producto incorrecto entregado',
                'description' => 'Pedí una pizza margarita y me entregaron una pizza pepperoni. Además, no incluyeron la bebida que había ordenado.',
                'priority' => 'high',
                'status' => 'in_progress',
            ],
            [
                'type' => 'peticion',
                'subject' => 'Horarios de atención extendidos',
                'description' => 'Sería posible extender los horarios de atención hasta las 2:00 AM los fines de semana?',
                'priority' => 'low',
                'status' => 'open',
            ],
            [
                'type' => 'queja',
                'subject' => 'Atención al cliente deficiente',
                'description' => 'El repartidor fue muy grosero y no siguió las instrucciones de entrega que especifiqué claramente.',
                'priority' => 'high',
                'status' => 'resolved',
            ],
            [
                'type' => 'reclamo',
                'subject' => 'Cobro duplicado',
                'description' => 'Me cobraron dos veces por el mismo pedido. Necesito que me devuelvan el dinero extra cobrado.',
                'priority' => 'high',
                'status' => 'in_progress',
            ],
            [
                'type' => 'peticion',
                'subject' => 'Información nutricional',
                'description' => 'Podrían incluir información nutricional detallada de todos los productos en la app?',
                'priority' => 'low',
                'status' => 'open',
            ],
            [
                'type' => 'queja',
                'subject' => 'Calidad de los ingredientes',
                'description' => 'Los vegetales de mi ensalada estaban marchitos y la carne tenía un sabor extraño.',
                'priority' => 'medium',
                'status' => 'resolved',
            ],
            [
                'type' => 'reclamo',
                'subject' => 'Promoción no aplicada',
                'description' => 'Usé el código DESCUENTO20 pero no me aplicaron el descuento prometido en mi pedido.',
                'priority' => 'medium',
                'status' => 'resolved',
            ],
            [
                'type' => 'peticion',
                'subject' => 'Opción de programar entregas',
                'description' => 'Me encantaría poder programar mis pedidos para que lleguen a una hora específica.',
                'priority' => 'medium',
                'status' => 'open',
            ],
        ];

        foreach ($pqrs as $index => $pqrData) {
            $client = $clients->random();
            $order = $orders->random();
            
            $createdAt = now()->subDays(rand(1, 30))->subHours(rand(0, 23));
            
            $pqr = Pqr::create([
                'ticket_number' => 'PQR-' . str_pad($index + 1, 6, '0', STR_PAD_LEFT),
                'user_id' => $client->id,
                'order_id' => rand(0, 3) === 0 ? $order->id : null, // 25% relacionados con pedidos
                'type' => $pqrData['type'],
                'subject' => $pqrData['subject'],
                'description' => $pqrData['description'],
                'priority' => $pqrData['priority'],
                'status' => $pqrData['status'],
                'customer_name' => $client->name,
                'customer_email' => $client->email,
                'customer_phone' => $client->phone,
                'created_at' => $createdAt,
                'updated_at' => $createdAt->addHours(rand(1, 48)),
            ]);

            // Agregar respuestas para PQRs resueltos o en progreso
            if (in_array($pqr->status, ['in_progress', 'resolved'])) {
                $this->createPqrResponses($pqr, $staff, $createdAt);
            }
        }
    }

    private function createPqrResponses(Pqr $pqr, $staff, $createdAt): void
    {
        $responses = [];

        if ($pqr->type === 'peticion') {
            $responses = [
                'Gracias por su sugerencia. La hemos enviado al equipo de desarrollo de menú para su evaluación.',
                'Estimado cliente, nos complace informarle que estamos trabajando en implementar las mejoras sugeridas.',
            ];
        } elseif ($pqr->type === 'queja') {
            $responses = [
                'Lamentamos profundamente los inconvenientes causados. Hemos iniciado una investigación interna.',
                'Como compensación por los inconvenientes, le hemos enviado un cupón de descuento del 30% para su próximo pedido.',
            ];
        } elseif ($pqr->type === 'reclamo') {
            $responses = [
                'Hemos verificado su caso y procederemos con el reembolso correspondiente en las próximas 24-48 horas.',
                'Le confirmo que el reembolso ha sido procesado exitosamente. Debería reflejarse en su cuenta en 3-5 días hábiles.',
            ];
        }

        foreach ($responses as $index => $responseText) {
            $staffMember = $staff->random();
            $responseDate = $createdAt->addHours(rand(2, 24 * ($index + 1)));
            
            PqrResponse::create([
                'pqr_id' => $pqr->id,
                'user_id' => $staffMember->id,
                'message' => $responseText,
                'response_type' => 'response',
                'is_public' => rand(0, 10) > 2, // 80% respuestas públicas
                'created_at' => $responseDate,
                'updated_at' => $responseDate,
            ]);
        }

        // Actualizar fecha de resolución si está resuelto
        if ($pqr->status === 'resolved') {
            $pqr->update([
                'resolved_at' => $createdAt->addHours(rand(6, 72)),
            ]);
        }
    }
}
