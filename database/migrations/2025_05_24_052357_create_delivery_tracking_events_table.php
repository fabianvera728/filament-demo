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
        Schema::create('delivery_tracking_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_id')->constrained()->onDelete('cascade');
            $table->string('event_type'); // status_change, location_update, note_added, photo_taken
            $table->string('status')->nullable(); // Estado de la entrega en este evento
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->text('description')->nullable();
            $table->string('photo_url')->nullable(); // URL de foto tomada
            $table->json('additional_data')->nullable(); // Datos adicionales del evento
            $table->timestamp('event_time');
            $table->timestamps();

            $table->index(['delivery_id']);
            $table->index(['event_type']);
            $table->index(['status']);
            $table->index(['event_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_tracking_events');
    }
};
