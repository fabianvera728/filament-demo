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
        Schema::create('zones', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('code', 10)->unique()->nullable();
            $table->json('coordinates')->nullable();
            $table->boolean('is_active')->default(true);
            $table->decimal('delivery_fee', 10, 2)->nullable();
            $table->decimal('min_order_amount', 10, 2)->nullable();
            $table->integer('estimated_delivery_time')->nullable(); // in minutes
            $table->json('coverage_area')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_active']);
            $table->index(['code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zones');
    }
};
