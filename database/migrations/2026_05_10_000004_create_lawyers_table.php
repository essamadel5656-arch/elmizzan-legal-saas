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
        Schema::create('lawyers', function (Blueprint $table) {
            $table->id();
            $table->string('name');// Full name of the lawyer
            $table->string('email')->unique();// Unique email address for contact
            $table->string('phone')->nullable();// Phone number for contact
            $table->string('specialization')->required();// Area of legal expertise
            $table->string('license_number')->unique();// Unique license number
            $table->string('address');// Mailing address
            $table->enum('degree', ['جدول_عام', 'ابتدائي', 'استئناف', 'نقض']);
            $table->string('national_id_image')->nullable();
            $table->string('bar_card_image')->nullable();
            $table->string('profile_image')->nullable();// Profile picture of the lawyer
            $table->text('bio');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lawyers');
    }
};
