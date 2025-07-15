<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_number')->unique();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('gateway')->default('wompi'); // wompi, openpay, globalpay, wenjoy, cash
            $table->string('payment_method'); // credit_card, debit_card, bank_transfer, cash, digital_wallet
            $table->string('status')->default('pending'); // pending, processing, completed, failed, cancelled, refunded, partial_refund
            $table->decimal('amount', 10, 2);
            $table->decimal('fee_amount', 8, 2)->default(0); // Comisión del gateway
            $table->decimal('net_amount', 10, 2); // Monto neto después de comisiones
            $table->string('currency', 3)->default('COP');
            
            // Información del gateway
            $table->string('gateway_transaction_id')->nullable();
            $table->string('gateway_reference')->nullable();
            $table->string('gateway_status')->nullable();
            $table->text('gateway_response')->nullable();
            $table->json('gateway_data')->nullable(); // Datos adicionales del gateway
            
            // Información de la tarjeta (si aplica)
            $table->string('card_last_four')->nullable();
            $table->string('card_brand')->nullable(); // visa, mastercard, amex, etc.
            $table->string('card_type')->nullable(); // credit, debit
            $table->string('card_holder_name')->nullable();
            
            // Tiempos
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->timestamp('refunded_at')->nullable();
            
            // Información adicional
            $table->text('failure_reason')->nullable();
            $table->text('notes')->nullable();
            $table->string('receipt_url')->nullable();
            $table->json('metadata')->nullable();
            $table->boolean('is_test')->default(false); // Si es una transacción de prueba
            
            // Información de reembolso
            $table->decimal('refunded_amount', 10, 2)->default(0);
            $table->string('refund_reference')->nullable();
            $table->text('refund_reason')->nullable();
            
            $table->timestamps();
            $table->softDeletes();

            $table->index(['order_id']);
            $table->index(['user_id']);
            $table->index(['payment_number']);
            $table->index(['gateway']);
            $table->index(['status']);
            $table->index(['gateway_transaction_id']);
            $table->index(['processed_at']);
            $table->index(['completed_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
