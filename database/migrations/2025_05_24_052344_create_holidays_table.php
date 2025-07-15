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
        Schema::create('holidays', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->date('date');
            $table->string('type')->default('national'); // national, regional, franchise_specific
            $table->boolean('affects_delivery')->default(true);
            $table->boolean('affects_operations')->default(true);
            $table->json('affected_zones')->nullable(); // IDs de zonas afectadas
            $table->json('affected_franchises')->nullable(); // IDs de franquicias afectadas
            $table->time('alternative_opening_time')->nullable();
            $table->time('alternative_closing_time')->nullable();
            $table->decimal('delivery_surcharge', 8, 2)->default(0); // Recargo por entrega en festivo
            $table->boolean('is_recurring')->default(false); // Si se repite anualmente
            $table->boolean('is_active')->default(true);
            $table->json('meta_data')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['date']);
            $table->index(['type']);
            $table->index(['affects_delivery']);
            $table->index(['affects_operations']);
            $table->index(['is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('holidays');
    }
};
