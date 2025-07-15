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
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->string('type')->default('discount'); // discount, promotion, coupon, loyalty
            $table->string('discount_type')->default('percentage'); // percentage, fixed_amount, free_shipping
            $table->decimal('discount_value', 10, 2);
            $table->decimal('minimum_order_amount', 10, 2)->default(0);
            $table->decimal('maximum_discount_amount', 10, 2)->nullable();
            $table->integer('usage_limit')->nullable(); // Límite total de usos
            $table->integer('usage_limit_per_customer')->nullable(); // Límite por cliente
            $table->integer('used_count')->default(0);
            
            // Fechas de validez
            $table->timestamp('starts_at');
            $table->timestamp('ends_at');
            
            // Restricciones
            $table->json('applicable_products')->nullable(); // IDs de productos aplicables
            $table->json('applicable_categories')->nullable(); // IDs de categorías aplicables
            $table->json('applicable_zones')->nullable(); // IDs de zonas aplicables
            $table->json('applicable_franchises')->nullable(); // IDs de franquicias aplicables
            $table->json('applicable_user_roles')->nullable(); // Roles de usuario aplicables
            $table->json('excluded_products')->nullable(); // Productos excluidos
            $table->json('excluded_categories')->nullable(); // Categorías excluidas
            
            // Configuración adicional
            $table->boolean('is_active')->default(true);
            $table->boolean('is_public')->default(true); // Si es visible públicamente
            $table->boolean('requires_code')->default(false); // Si requiere código de cupón
            $table->boolean('stackable')->default(false); // Si se puede combinar con otras campañas
            $table->string('priority')->default('normal'); // low, normal, high
            $table->json('conditions')->nullable(); // Condiciones adicionales
            $table->json('meta_data')->nullable();
            
            $table->timestamps();
            $table->softDeletes();

            $table->index(['code']);
            $table->index(['type']);
            $table->index(['is_active']);
            $table->index(['starts_at']);
            $table->index(['ends_at']);
            $table->index(['priority']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
