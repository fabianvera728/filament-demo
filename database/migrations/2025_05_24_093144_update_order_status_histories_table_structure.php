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
            // Agregar la columna 'status' que el modelo espera
            $table->string('status')->after('order_id')->nullable();
            
            // Agregar 'changed_by' si no existe (renombrando user_id)
            $table->renameColumn('user_id', 'changed_by');
            
            // Agregar 'changed_at' para timestamp específico
            $table->timestamp('changed_at')->after('notes')->nullable();
            
            // Mantener las columnas existentes por compatibilidad
            // pero marcar previous_status como nullable por si acaso
            $table->string('previous_status')->nullable()->change();
        });

        // Migrar datos existentes si los hay
        DB::statement('UPDATE order_status_histories SET status = new_status WHERE status IS NULL');
        DB::statement('UPDATE order_status_histories SET changed_at = created_at WHERE changed_at IS NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_status_histories', function (Blueprint $table) {
            // Revertir cambios
            $table->dropColumn(['status', 'changed_at']);
            $table->renameColumn('changed_by', 'user_id');
        });
    }
};
