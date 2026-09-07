<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courts', function (Blueprint $table) {
            $table->dropForeign(['court_level_id']);
            $table->dropColumn('court_level_id');
        });
    }

    public function down(): void
    {
        Schema::table('courts', function (Blueprint $table) {
            $table->foreignId('court_level_id')
                  ->nullable()
                  ->constrained('court_levels')
                  ->onDelete('set null');
        });
    }
};
