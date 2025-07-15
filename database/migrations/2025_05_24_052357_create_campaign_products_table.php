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
        Schema::create('campaign_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->decimal('specific_discount_value', 10, 2)->nullable(); // Descuento específico para este producto
            $table->string('specific_discount_type')->nullable(); // percentage, fixed_amount
            $table->boolean('is_featured')->default(false); // Si es producto destacado en la campaña
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['campaign_id', 'product_id']);
            $table->index(['campaign_id']);
            $table->index(['product_id']);
            $table->index(['is_featured']);
            $table->index(['sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaign_products');
    }
};
