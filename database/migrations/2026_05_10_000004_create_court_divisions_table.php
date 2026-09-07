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
        Schema::create('court_divisions', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // اسم الدائرة (مثل: الدائرة 5 مدني، جنح مستأنف)

            // ربط الدائرة بالمحكمة (لو المحكمة اتحذفت، الدوائر بتاعتها تتحذف تلقائياً)
            $table->foreignId('court_id')
                  ->constrained('courts')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('court_divisions');
    }
};
