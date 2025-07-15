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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('franchise_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('zone_id')->nullable()->constrained()->onDelete('set null');
            $table->string('status')->default('pending'); // pending, confirmed, preparing, ready, dispatched, delivered, cancelled
            $table->string('payment_status')->default('pending'); // pending, paid, failed, refunded, partial
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('shipping_amount', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            $table->string('currency', 3)->default('COP');
            
            // Información del cliente
            $table->string('customer_name');
            $table->string('customer_email')->nullable();
            $table->string('customer_phone');
            $table->text('customer_notes')->nullable();
            
            // Dirección de entrega
            $table->string('delivery_address');
            $table->string('delivery_city');
            $table->string('delivery_state')->nullable();
            $table->string('delivery_postal_code')->nullable();
            $table->string('delivery_country')->default('Colombia');
            $table->decimal('delivery_latitude', 10, 8)->nullable();
            $table->decimal('delivery_longitude', 11, 8)->nullable();
            $table->text('delivery_instructions')->nullable();
            
            // Información de facturación
            $table->string('billing_address')->nullable();
            $table->string('billing_city')->nullable();
            $table->string('billing_state')->nullable();
            $table->string('billing_postal_code')->nullable();
            $table->string('billing_country')->nullable();
            
            // Tiempos
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('prepared_at')->nullable();
            $table->timestamp('dispatched_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamp('estimated_delivery_time')->nullable();
            
            // Información adicional
            $table->string('delivery_method')->default('delivery'); // delivery, pickup
            $table->string('payment_method')->nullable();
            $table->json('applied_coupons')->nullable();
            $table->json('meta_data')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->decimal('tip_amount', 8, 2)->default(0);
            
            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id']);
            $table->index(['franchise_id']);
            $table->index(['zone_id']);
            $table->index(['status']);
            $table->index(['payment_status']);
            $table->index(['order_number']);
            $table->index(['created_at']);
            $table->index(['confirmed_at']);
            $table->index(['delivered_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
