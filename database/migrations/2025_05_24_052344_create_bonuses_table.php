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
        Schema::create('bonuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type'); // points, discount, free_product, cashback, delivery_credit
            $table->string('source'); // order, referral, birthday, loyalty, promotion, manual
            $table->string('status')->default('active'); // active, used, expired, cancelled
            $table->decimal('value', 10, 2); // Valor del bono
            $table->decimal('used_value', 10, 2)->default(0); // Valor ya utilizado
            $table->decimal('remaining_value', 10, 2); // Valor restante
            $table->string('currency', 3)->default('COP');
            
            // Información de origen
            $table->foreignId('source_order_id')->nullable()->constrained('orders')->onDelete('set null');
            $table->foreignId('source_user_id')->nullable()->constrained('users')->onDelete('set null'); // Usuario que generó el bono (referidos)
            $table->string('source_reference')->nullable(); // Referencia adicional
            
            // Restricciones de uso
            $table->decimal('minimum_order_amount', 10, 2)->default(0);
            $table->json('applicable_products')->nullable(); // IDs de productos aplicables
            $table->json('applicable_categories')->nullable(); // IDs de categorías aplicables
            $table->json('applicable_franchises')->nullable(); // IDs de franquicias aplicables
            $table->json('excluded_products')->nullable(); // Productos excluidos
            
            // Fechas
            $table->timestamp('earned_at');
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('used_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            
            // Información adicional
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('terms_conditions')->nullable();
            $table->boolean('is_transferable')->default(false);
            $table->boolean('is_combinable')->default(true); // Si se puede combinar con otros bonos
            $table->json('usage_history')->nullable(); // Historial de uso parcial
            $table->json('meta_data')->nullable();
            $table->text('cancellation_reason')->nullable();
            
            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id']);
            $table->index(['source_order_id']);
            $table->index(['source_user_id']);
            $table->index(['type']);
            $table->index(['source']);
            $table->index(['status']);
            $table->index(['earned_at']);
            $table->index(['expires_at']);
            $table->index(['used_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bonuses');
    }
};
