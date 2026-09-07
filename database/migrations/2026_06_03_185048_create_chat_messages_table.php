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
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            
            // ربط الرسالة بالجلسة التابعة لها
            $table->foreignId('chat_session_id')->constrained('chat_sessions')->onDelete('cascade');
            
            // مين اللي بعت الرسالة؟ (user أو model) عشان نعرف نفرق بينهم في العرض
            $table->enum('role', ['user', 'model']);
            
            // نص الرسالة نفسه (جعلناه nullable في حال أرسل المحامي ملف فقط بدون نص)
            $table->text('message')->nullable();

            // الأعمدة الجديدة الخاصة بالملفات والمرفقات 
            $table->string('file_path')->nullable()->comment('مسار الملف المرفع على السيرفر');
            $table->string('file_name')->nullable()->comment('اسم الملف الأصلي للعرض في الواجهة');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
    }
};