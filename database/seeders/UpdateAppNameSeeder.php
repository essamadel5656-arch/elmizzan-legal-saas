<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

/**
 * Inline DB update: sets app_name -> 'الميزان' without wiping existing case data.
 * Run with: php artisan db:seed --class=UpdateAppNameSeeder
 */
class UpdateAppNameSeeder extends Seeder
{
    public function run(): void
    {
        Setting::set('app_name', 'الميزان');
        $this->command->info('app_name updated to الميزان in settings table.');
    }
}

