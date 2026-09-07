<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
{
    Schema::table('users', function (Blueprint $table) {
        // تحديد الحقل كـ enum والقيمة الافتراضية هي المجانية
        $table->enum('plan_type', ['free', 'premium'])->default('free'); 
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('plan_type');
    });
}
};
