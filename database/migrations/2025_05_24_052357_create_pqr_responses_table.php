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
        Schema::create('pqr_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pqr_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Usuario que responde
            $table->text('message');
            $table->string('response_type')->default('response'); // response, internal_note, status_change
            $table->boolean('is_public')->default(true); // Si es visible para el cliente
            $table->json('attachments')->nullable(); // URLs de archivos adjuntos
            $table->json('meta_data')->nullable();
            $table->timestamps();

            $table->index(['pqr_id']);
            $table->index(['user_id']);
            $table->index(['response_type']);
            $table->index(['is_public']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pqr_responses');
    }
};
