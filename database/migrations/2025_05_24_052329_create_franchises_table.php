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
        Schema::create('franchises', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->string('address');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->decimal('commission_percentage', 5, 2)->default(0);
            $table->time('opening_time')->default('08:00:00');
            $table->time('closing_time')->default('22:00:00');
            $table->json('operating_days')->nullable(); // Array de días de la semana
            $table->decimal('delivery_radius_km', 8, 2)->default(5);
            $table->decimal('minimum_order_amount', 10, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->string('manager_name')->nullable();
            $table->string('manager_phone')->nullable();
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
        Schema::dropIfExists('franchises');
    }
};
