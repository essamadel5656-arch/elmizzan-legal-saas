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
        Schema::create('clients', function (Blueprint $table) {
            $table->id()->unique();
            $table->timestamps();
            $table->string('name', 100);
            $table->string('address', 100);
            $table->string('email', 100)->unique();
            $table->string('phone', 11);
            $table->string('nid', 14)->unique();
            $table->string('note', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
