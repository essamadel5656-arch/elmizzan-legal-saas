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
    Schema::create('chat_sessions', function (Blueprint $table) {
        $table->id();
        // ربط الجلسة بالمحامي (المستخدم الحالي)
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        // عنوان اختياري للشات (مثل: استشارة قضية أحمد، أو صياغة عقد..)
        $table->string('title')->nullable()->default('محادثة جديدة');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_sessions');
    }
};
