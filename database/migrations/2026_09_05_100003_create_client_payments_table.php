<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('client_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_id')->constrained('cases')->onDelete('cascade');
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->enum('mode', ['in_person', 'transfer'])->default('in_person');
            $table->decimal('amount', 12, 2);
            $table->date('payment_date')->nullable();
            $table->string('reference_number')->nullable(); // for transfer mode
            $table->string('receipt_path')->nullable();     // screenshot for transfer mode
            $table->enum('status', ['pending', 'pending_verification', 'confirmed'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_payments');
    }
};
