<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Seed the ai_feature_enabled setting key (default disabled)
        // This uses raw insert with updateOrInsert to be idempotent
        DB::table('settings')->updateOrInsert(
            ['key' => 'ai_feature_enabled'],
            ['key' => 'ai_feature_enabled', 'value' => '0', 'updated_at' => now(), 'created_at' => now()]
        );

        // Seed payment setting keys
        $defaults = [
            'bank_name'       => '',
            'bank_iban'       => '',
            'instapay_address'=> '',
            'mobile_wallet'   => '',
        ];
        foreach ($defaults as $key => $value) {
            DB::table('settings')->updateOrInsert(
                ['key' => $key],
                ['key' => $key, 'value' => $value, 'updated_at' => now(), 'created_at' => now()]
            );
        }
    }

    public function down(): void
    {
        DB::table('settings')->whereIn('key', [
            'ai_feature_enabled', 'bank_name', 'bank_iban', 'instapay_address', 'mobile_wallet',
        ])->delete();
    }
};
