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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->nullable(); // Para usuarios no registrados
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // Para usuarios registrados
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('product_name'); // Guardar nombre por si el producto cambia
            $table->string('product_sku')->nullable();
            $table->decimal('product_price', 10, 2); // Precio al momento de agregar
            $table->integer('quantity');
            $table->json('product_options')->nullable(); // Opciones como tamaño, color, etc.
            $table->decimal('total_price', 10, 2); // quantity * product_price
            $table->timestamps();

            $table->index(['session_id']);
            $table->index(['user_id']);
            $table->index(['product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
