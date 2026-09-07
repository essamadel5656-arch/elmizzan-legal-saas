<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Link to clients table for client-role users
            $table->foreignId('client_id')->nullable()->after('lawyer_id')
                  ->constrained('clients')->nullOnDelete();
            // Secure one-time activation token for client self-service password setup
            $table->string('activation_token')->nullable()->after('client_id');
            $table->timestamp('activation_token_expires_at')->nullable()->after('activation_token');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['client_id']);
            $table->dropColumn(['client_id', 'activation_token', 'activation_token_expires_at']);
        });
    }
};
