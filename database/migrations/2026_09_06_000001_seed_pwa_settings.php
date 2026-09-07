<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Setting;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Setting::set('pwa_short_name', 'الميزان');
        Setting::set('pwa_theme_color', '#1e293b');
        Setting::set('pwa_background_color', '#f8fafc');
        Setting::set('pwa_description', 'نظام إدارة القضايا القانونية المتكاملة');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $keys = ['pwa_short_name', 'pwa_theme_color', 'pwa_background_color', 'pwa_description'];
        Setting::whereIn('key', $keys)->delete();
        foreach ($keys as $key) {
            \Illuminate\Support\Facades\Cache::forget("setting_{$key}");
        }
    }
};
