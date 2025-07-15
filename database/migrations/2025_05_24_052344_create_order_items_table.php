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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('product_name'); // Snapshot del nombre del producto
            $table->string('product_sku'); // Snapshot del SKU
            $table->text('product_description')->nullable(); // Snapshot de la descripción
            $table->string('product_image')->nullable(); // Snapshot de la imagen
            $table->decimal('unit_price', 10, 2); // Precio unitario al momento de la compra
            $table->integer('quantity');
            $table->decimal('total_price', 10, 2); // unit_price * quantity
            $table->json('product_options')->nullable(); // Variantes, personalizaciones, etc.
            $table->json('meta_data')->nullable(); // Datos adicionales
            $table->timestamps();

            $table->index(['order_id']);
            $table->index(['product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
