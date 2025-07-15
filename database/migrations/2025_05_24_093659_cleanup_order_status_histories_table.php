<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('order_status_histories', function (Blueprint $table) {
            // Primero eliminar los índices que dependen de las columnas que vamos a eliminar
            $table->dropIndex(['new_status']);
        });

        Schema::table('order_status_histories', function (Blueprint $table) {
            // Ahora eliminar las columnas obsoletas
            $table->dropColumn(['new_status', 'previous_status', 'meta_data']);
            
            // Hacer que la columna status sea NOT NULL ya que siempre debe tener un valor
            $table->string('status')->nullable(false)->change();
            
            // Hacer que changed_at sea NOT NULL también
            $table->timestamp('changed_at')->nullable(false)->change();
        });

        Schema::table('order_status_histories', function (Blueprint $table) {
            // Agregar un índice para la nueva columna status
            $table->index(['status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_status_histories', function (Blueprint $table) {
            // Eliminar el nuevo índice
            $table->dropIndex(['status']);
        });

        Schema::table('order_status_histories', function (Blueprint $table) {
            // Restaurar las columnas eliminadas
            $table->string('new_status')->nullable();
            $table->string('previous_status')->nullable();
            $table->json('meta_data')->nullable();
            
            // Hacer que status y changed_at sean nullable nuevamente
            $table->string('status')->nullable()->change();
            $table->timestamp('changed_at')->nullable()->change();
        });

        Schema::table('order_status_histories', function (Blueprint $table) {
            // Restaurar el índice original
            $table->index(['new_status']);
        });
    }
};
