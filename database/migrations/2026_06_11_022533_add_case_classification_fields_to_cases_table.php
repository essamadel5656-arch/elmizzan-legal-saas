<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cases', function (Blueprint $table) {

$table->foreignId('jurisdiction_id')
      ->nullable()
      ->constrained('jurisdictions')
      ->nullOnDelete();

            $table->foreignId('court_level_id')
                  ->nullable()
                  ->after('jurisdiction_id')
                  ->constrained('court_levels')
                  ->nullOnDelete();

            $table->string('circuit')
                  ->nullable()
                  ->after('court_level_id');
        });
    }

    public function down(): void
    {
        Schema::table('cases', function (Blueprint $table) {

            $table->dropForeign(['jurisdiction_id']);
            $table->dropForeign(['court_level_id']);

            $table->dropColumn([
                'jurisdiction_id',
                'court_level_id',
                'circuit'
            ]);
        });
    }
};
