<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cases', function (Blueprint $table) {
            // Admin-only field: the officially agreed legal fee for this case
            $table->decimal('agreed_legal_fee', 12, 2)->nullable()->after('total_costs');
        });
    }

    public function down(): void
    {
        Schema::table('cases', function (Blueprint $table) {
            $table->dropColumn('agreed_legal_fee');
        });
    }
};
