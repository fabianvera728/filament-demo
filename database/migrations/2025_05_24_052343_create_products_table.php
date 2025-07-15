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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();
            $table->string('sku')->unique();
            $table->string('barcode')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('cost_price', 10, 2)->nullable();
            $table->decimal('compare_price', 10, 2)->nullable(); // Precio de comparación
            $table->integer('stock_quantity')->default(0);
            $table->integer('min_stock_level')->default(0);
            $table->boolean('track_inventory')->default(true);
            $table->boolean('allow_backorder')->default(false);
            $table->decimal('weight', 8, 3)->nullable(); // En kg
            $table->json('dimensions')->nullable(); // {length, width, height}
            $table->json('images')->nullable(); // Array de URLs de imágenes
            $table->string('featured_image')->nullable();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('franchise_id')->nullable()->constrained()->onDelete('set null');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_digital')->default(false);
            $table->boolean('requires_shipping')->default(true);
            $table->json('attributes')->nullable(); // Atributos personalizados
            $table->json('variants')->nullable(); // Variantes del producto
            $table->json('tags')->nullable(); // Tags para búsqueda
            $table->json('seo_data')->nullable(); // Meta title, description, keywords
            $table->integer('sort_order')->default(0);
            $table->decimal('rating_average', 3, 2)->default(0);
            $table->integer('rating_count')->default(0);
            $table->integer('view_count')->default(0);
            $table->integer('purchase_count')->default(0);
            $table->timestamp('available_from')->nullable();
            $table->timestamp('available_until')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['category_id']);
            $table->index(['franchise_id']);
            $table->index(['is_active']);
            $table->index(['is_featured']);
            $table->index(['price']);
            $table->index(['stock_quantity']);
            $table->index(['sort_order']);
            $table->index(['sku']);
            $table->index(['name']); // Índice normal en lugar de fulltext
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
