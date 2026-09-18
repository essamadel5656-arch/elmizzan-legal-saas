<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
echo "\napp()->getLocale() = " . app()->getLocale() . "\n";
echo "tenant_setting('app_locale') = " . \App\Models\Setting::where('key', 'app_locale')->value('value') . "\n";
