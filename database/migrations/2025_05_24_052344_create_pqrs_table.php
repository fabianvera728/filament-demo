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
        Schema::create('pqrs', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique();
            $table->string('type'); // petition, complaint, claim, suggestion
            $table->string('subject');
            $table->text('description');
            $table->string('status')->default('open'); // open, in_progress, resolved, closed, escalated
            $table->string('priority')->default('medium'); // low, medium, high, urgent
            $table->string('category')->nullable(); // delivery, product, service, billing, etc.
            
            // Información del solicitante
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone')->nullable();
            $table->string('customer_document')->nullable();
            
            // Información relacionada
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('franchise_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            
            // Archivos adjuntos
            $table->json('attachments')->nullable(); // URLs de archivos adjuntos
            
            // Tiempos
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('first_response_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamp('escalated_at')->nullable();
            $table->timestamp('due_date')->nullable();
            
            // Información adicional
            $table->text('resolution')->nullable();
            $table->text('internal_notes')->nullable();
            $table->integer('satisfaction_rating')->nullable(); // 1-5
            $table->text('satisfaction_feedback')->nullable();
            $table->json('tags')->nullable(); // Tags para categorización
            $table->json('meta_data')->nullable();
            
            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id']);
            $table->index(['order_id']);
            $table->index(['product_id']);
            $table->index(['franchise_id']);
            $table->index(['assigned_to']);
            $table->index(['ticket_number']);
            $table->index(['type']);
            $table->index(['status']);
            $table->index(['priority']);
            $table->index(['category']);
            $table->index(['created_at']);
            $table->index(['due_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pqrs');
    }
};
