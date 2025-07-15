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
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->string('delivery_number')->unique();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('delivery_person_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('zone_id')->nullable()->constrained()->onDelete('set null');
            $table->string('status')->default('pending'); // pending, assigned, picked_up, in_transit, delivered, failed, cancelled
            $table->string('delivery_type')->default('standard'); // standard, express, scheduled
            
            // Información de entrega
            $table->string('delivery_address');
            $table->string('delivery_city');
            $table->string('delivery_state')->nullable();
            $table->string('delivery_postal_code')->nullable();
            $table->decimal('delivery_latitude', 10, 8)->nullable();
            $table->decimal('delivery_longitude', 11, 8)->nullable();
            $table->text('delivery_instructions')->nullable();
            
            // Información del destinatario
            $table->string('recipient_name');
            $table->string('recipient_phone');
            $table->string('recipient_email')->nullable();
            
            // Tiempos
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('picked_up_at')->nullable();
            $table->timestamp('in_transit_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamp('estimated_delivery_time')->nullable();
            $table->timestamp('scheduled_delivery_time')->nullable();
            
            // Información adicional
            $table->decimal('delivery_fee', 8, 2)->default(0);
            $table->decimal('tip_amount', 8, 2)->default(0);
            $table->decimal('distance_km', 8, 2)->nullable();
            $table->integer('estimated_duration_minutes')->nullable();
            $table->text('delivery_notes')->nullable();
            $table->text('failure_reason')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->string('proof_of_delivery')->nullable(); // URL de imagen/firma
            $table->decimal('current_latitude', 10, 8)->nullable();
            $table->decimal('current_longitude', 11, 8)->nullable();
            $table->timestamp('last_location_update')->nullable();
            $table->json('route_data')->nullable(); // Datos de la ruta
            $table->json('meta_data')->nullable();
            
            $table->timestamps();
            $table->softDeletes();

            $table->index(['order_id']);
            $table->index(['delivery_person_id']);
            $table->index(['zone_id']);
            $table->index(['status']);
            $table->index(['delivery_number']);
            $table->index(['assigned_at']);
            $table->index(['delivered_at']);
            $table->index(['estimated_delivery_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
