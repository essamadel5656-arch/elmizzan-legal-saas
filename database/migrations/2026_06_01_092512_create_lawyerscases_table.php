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
        Schema::create('lawyerscases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lawyer_id')->constrained()->onDelete('cascade');
            $table->foreignId('case_id')->constrained()->onDelete('cascade');
            $table->enum('role', ['lead', 'assistant'])->default('lead');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lawyerscases');
    }
};
