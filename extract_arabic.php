<?php
$directory = 'resources/views/livewire';

$files_to_check = [
    'appointments/appointment-create.blade.php',
    'appointments/appointment-edit.blade.php',
    'lawyers/lawyer-create.blade.php',
    'lawyers/lawyer-edit.blade.php',
    'clients/client-create.blade.php',
    'clients/client-edit.blade.php',
    'courts/court-create.blade.php',
    'courts/court-edit.blade.php',
    'payments/client-payment-create.blade.php',
    'jurisdictions/jurisdiction-create.blade.php',
    'jurisdictions/jurisdiction-edit.blade.php'
];

$matches = [];

foreach ($files_to_check as $file) {
    $path = $directory . '/' . $file;
    if (file_exists($path)) {
        $content = file_get_contents($path);
        
        // Extract from >...<
        preg_match_all('/>([^<]*[\x{0600}-\x{06FF}]+[^<]*)</u', $content, $tags);
        foreach ($tags[1] as $t) {
            $matches[] = trim($t);
        }
        
        // Extract from placeholder="..."
        preg_match_all('/placeholder="([^"]*[\x{0600}-\x{06FF}]+[^"]*)"/u', $content, $placeholders);
        foreach ($placeholders[1] as $p) {
            $matches[] = trim($p);
        }
        
        // Extract from value="..."
        preg_match_all('/value="([^"]*[\x{0600}-\x{06FF}]+[^"]*)"/u', $content, $values);
        foreach ($values[1] as $v) {
            $matches[] = trim($v);
        }
    }
}

$matches = array_unique($matches);
$matches = array_filter($matches, function($m) { return !empty($m); });

file_put_contents('arabic_strings.txt', implode("\n", $matches));
echo "Done.\n";
