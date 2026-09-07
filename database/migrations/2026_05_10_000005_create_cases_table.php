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
        Schema::create('cases', function (Blueprint $table) {
            $table->id();

            // بيانات القضية الأساسية
            $table->string('case_number', 200);

            // ضبط حالات القضية بناءً على اختيارك (تم توحيد جديدة ومفتوحة لتبدأ بـ 'مفتوحة')
            $table->enum('status', [
                'مفتوحة',       // القضية شغالين عليها حالياً بنشاط
                'متداولة',      // بالجلسات
                'مؤجلة',        // مؤجلة لجلسة قادمة
                'محجوزة للحكم',  // في مرحلة النطق بالحكم
                'منتهية',       // صدر حكم ابتدائي أو نهائي
                'مستأنفة',      // تم الطعن عليها
                'محفوظة',       // تم أرشفتها في المكتب
                'معلقة'         // متوقفة لسبب قانوني
            ])->default('مفتوحة');

            $table->text('description')->nullable();

            // الحسابات والمبالغ المالية (تم تكبير الخانات لتتحمل مبالغ كبيرة تصل لـ 99 مليون)
            $table->decimal('costs', 12, 2)->default(0.00);       // المصاريف الإدارية
            $table->decimal('total_costs', 12, 2)->default(0.00); // أتعاب المحاماة الإجمالية
            $table->decimal('deposit', 12, 2)->default(0.00);     // المقدم / المدفوع

            // سير القضية ومرفقاتها (التوكيل والورق الرسمي)
            $table->text('Previous_procedure')->nullable();        // الإجراء السابق
            $table->string('case_file')->nullable();               // مرفقات القضية (صورة التوكيل، عريضة الدعوى، إلخ)
            $table->string('procuration')->nullable();             // رقم أو نص التوكيل (تمت إضافته لحل المشكلة)
            $table->string('final_decision')->nullable();          // الحكم النهائي
            $table->text('notes')->nullable();                     // ملاحظات إضافية

            // بيانات الخصم (الطرف الثاني)
            $table->string('rival_name');
            $table->string('rival_number', 15)->nullable();
            $table->string('rival_address')->nullable();
            $table->string('rival_nid', 14)->nullable();           // الرقم القومي للخصم

            // العلاقات والربط بالجداول الأخرى (Foreign Keys)
            $table->foreignId('court_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
$table->unsignedBigInteger('lawyer_id');



$table->foreign('lawyer_id')
      ->references('id')
      ->on('lawyers')
      ->onDelete('cascade')
      ->onUpdate('cascade');            // وقت الإنشاء والتحديث
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cases');
    }
};
