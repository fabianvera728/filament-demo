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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'office', 'client', 'partner', 'delivery'])->default('client');
            $table->string('phone', 20)->nullable();
            $table->text('address')->nullable();
            $table->foreignId('zone_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('franchise_id')->nullable()->constrained()->onDelete('set null');
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_login_at')->nullable();
            $table->string('profile_image')->nullable();
            $table->date('birth_date')->nullable();
            $table->enum('document_type', ['cc', 'ce', 'passport', 'nit'])->nullable();
            $table->string('document_number', 20)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role',
                'phone',
                'address',
                'zone_id',
                'franchise_id',
                'is_active',
                'last_login_at',
                'profile_image',
                'birth_date',
                'document_type',
                'document_number'
            ]);
        });
    }
};
