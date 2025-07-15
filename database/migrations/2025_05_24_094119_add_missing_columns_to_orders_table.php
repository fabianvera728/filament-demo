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
        Schema::table('orders', function (Blueprint $table) {
            // Agregar columnas faltantes que están en el modelo Order
            $table->string('tracking_number')->nullable()->after('cancelled_at');
            $table->string('payment_reference')->nullable()->after('tracking_number');
            $table->timestamp('actual_delivery_time')->nullable()->after('estimated_delivery_time');
            $table->timestamp('scheduled_delivery_date')->nullable()->after('actual_delivery_time');
            $table->timestamp('completed_at')->nullable()->after('scheduled_delivery_date');
            $table->text('notes')->nullable()->after('completed_at');
            $table->text('special_instructions')->nullable()->after('notes');
            $table->json('delivery_coordinates')->nullable()->after('delivery_instructions');
            
            // Agregar índices para las nuevas columnas importantes
            $table->index(['tracking_number']);
            $table->index(['payment_reference']);
            $table->index(['completed_at']);
            $table->index(['scheduled_delivery_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Eliminar los índices primero
            $table->dropIndex(['tracking_number']);
            $table->dropIndex(['payment_reference']);
            $table->dropIndex(['completed_at']);
            $table->dropIndex(['scheduled_delivery_date']);
            
            // Eliminar las columnas agregadas
            $table->dropColumn([
                'tracking_number',
                'payment_reference',
                'actual_delivery_time',
                'scheduled_delivery_date',
                'completed_at',
                'notes',
                'special_instructions',
                'delivery_coordinates'
            ]);
        });
    }
};
