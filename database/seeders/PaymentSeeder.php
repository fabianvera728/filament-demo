<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Payment;
use App\Models\Order;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders = Order::whereIn('payment_status', ['paid', 'failed', 'refunded'])->get();

        if ($orders->isEmpty()) {
            return;
        }

        foreach ($orders as $order) {
            $gateway = $this->getGateway($order->payment_method);
            $status = $this->getPaymentStatus($order->payment_status);
            $amount = $order->total_amount;
            $feeAmount = $amount * 0.035; // 3.5% fee aprox
            $netAmount = $amount - $feeAmount;
            
            $payment = Payment::create([
                'payment_number' => 'PAY-' . str_pad($order->id, 6, '0', STR_PAD_LEFT),
                'order_id' => $order->id,
                'user_id' => $order->user_id,
                'gateway' => $gateway,
                'payment_method' => $order->payment_method,
                'status' => $status,
                'amount' => $amount,
                'fee_amount' => $gateway !== 'cash' ? $feeAmount : 0,
                'net_amount' => $gateway !== 'cash' ? $netAmount : $amount,
                'currency' => $order->currency,
                'gateway_transaction_id' => $this->generateTransactionId($gateway),
                'gateway_response' => json_encode($this->generateGatewayResponse($status, $gateway)),
                'processed_at' => $status !== 'pending' ? $order->created_at->addMinutes(rand(1, 30)) : null,
                'completed_at' => $status === 'completed' ? $order->created_at->addMinutes(rand(1, 30)) : null,
                'failed_at' => $status === 'failed' ? $order->created_at->addMinutes(rand(1, 30)) : null,
                'is_test' => false,
                'created_at' => $order->created_at->addMinutes(rand(1, 10)),
                'updated_at' => $order->updated_at,
            ]);

            // Crear pagos adicionales para refunds si es necesario
            if ($order->payment_status === 'refunded') {
                Payment::create([
                    'payment_number' => 'REF-' . str_pad($order->id, 6, '0', STR_PAD_LEFT),
                    'order_id' => $order->id,
                    'user_id' => $order->user_id,
                    'gateway' => $gateway,
                    'payment_method' => 'refund',
                    'status' => 'completed',
                    'amount' => -$order->total_amount, // Negativo para refund
                    'fee_amount' => 0,
                    'net_amount' => -$order->total_amount,
                    'currency' => $order->currency,
                    'gateway_transaction_id' => $this->generateTransactionId($gateway, 'refund'),
                    'gateway_response' => json_encode($this->generateRefundResponse($gateway)),
                    'refunded_amount' => $order->total_amount,
                    'refund_reference' => 'REF_' . time(),
                    'refund_reason' => 'Customer request',
                    'processed_at' => $order->created_at->addDays(rand(1, 5)),
                    'completed_at' => $order->created_at->addDays(rand(1, 5)),
                    'refunded_at' => $order->created_at->addDays(rand(1, 5)),
                    'is_test' => false,
                    'created_at' => $order->created_at->addDays(rand(1, 5)),
                    'updated_at' => $order->created_at->addDays(rand(1, 6)),
                ]);
            }
        }
    }

    private function getGateway($paymentMethod): string
    {
        return match($paymentMethod) {
            'credit_card', 'debit_card' => ['wompi', 'openpay', 'globalpay'][array_rand(['wompi', 'openpay', 'globalpay'])],
            'digital_wallet' => 'wenjoy',
            'cash' => 'cash',
            default => 'wompi'
        };
    }

    private function getPaymentStatus($orderPaymentStatus): string
    {
        return match($orderPaymentStatus) {
            'paid' => 'completed',
            'failed' => 'failed',
            'refunded' => 'completed', // El pago original fue completado antes del refund
            default => 'pending'
        };
    }

    private function generateTransactionId($gateway, $type = 'payment'): string
    {
        $prefix = match($gateway) {
            'wompi' => 'WMP',
            'openpay' => 'OPY',
            'globalpay' => 'GLB',
            'wenjoy' => 'WNJ',
            'cash' => 'CSH',
            default => 'TXN'
        };

        if ($type === 'refund') {
            $prefix .= '_REF';
        }

        return $prefix . '_' . strtoupper(uniqid()) . '_' . time();
    }

    private function generateGatewayResponse($status, $gateway): array
    {
        $baseResponse = [
            'gateway' => $gateway,
            'timestamp' => now()->toISOString(),
            'processor_response_code' => $status === 'completed' ? '00' : '05',
        ];

        if ($gateway === 'wompi') {
            return array_merge($baseResponse, [
                'id' => 'txn_' . uniqid(),
                'status' => $status === 'completed' ? 'APPROVED' : 'DECLINED',
                'status_message' => $status === 'completed' ? 'Transacción aprobada' : 'Transacción declinada',
                'reference' => 'REF_' . time(),
                'payment_method_type' => 'CARD',
                'taxes' => [
                    ['type' => 'vat', 'amount_in_cents' => 0]
                ]
            ]);
        } elseif ($gateway === 'openpay') {
            return array_merge($baseResponse, [
                'id' => 'tr' . uniqid(),
                'status' => $status === 'completed' ? 'completed' : 'failed',
                'method' => 'card',
                'operation_type' => 'in',
                'card' => [
                    'type' => 'credit',
                    'brand' => ['visa', 'mastercard', 'amex'][array_rand(['visa', 'mastercard', 'amex'])],
                    'card_number' => '************' . rand(1000, 9999)
                ]
            ]);
        } elseif ($gateway === 'globalpay') {
            return array_merge($baseResponse, [
                'transaction_id' => 'GP' . time() . rand(1000, 9999),
                'result_code' => $status === 'completed' ? 'APPROVED' : 'DECLINED',
                'result_message' => $status === 'completed' ? 'Transaction Approved' : 'Transaction Declined',
                'auth_code' => $status === 'completed' ? strtoupper(uniqid()) : null,
            ]);
        } elseif ($gateway === 'wenjoy') {
            return array_merge($baseResponse, [
                'transaction_id' => 'WJ_' . time(),
                'status' => $status === 'completed' ? 'SUCCESS' : 'FAILED',
                'wallet_type' => 'digital',
                'balance_after' => rand(10000, 500000),
            ]);
        } else { // cash
            return array_merge($baseResponse, [
                'payment_type' => 'cash',
                'received_amount' => rand(1, 2) === 1 ? null : (rand(50000, 100000)),
                'change_amount' => 0,
                'status' => 'received',
            ]);
        }
    }

    private function generateRefundResponse($gateway): array
    {
        return [
            'gateway' => $gateway,
            'refund_id' => 'REF_' . uniqid(),
            'status' => 'REFUNDED',
            'status_message' => 'Reembolso procesado exitosamente',
            'refund_reason' => ['customer_request', 'order_cancelled', 'quality_issue'][array_rand(['customer_request', 'order_cancelled', 'quality_issue'])],
            'processed_at' => now()->toISOString(),
        ];
    }
}
