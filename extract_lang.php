<?php

$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__ . '/resources/views'));
$keys = [];

foreach ($files as $file) {
    if ($file->isFile() && $file->getExtension() == 'php') {
        $content = file_get_contents($file->getPathname());
        
        // Match __('...')
        preg_match_all('/__\(\s*[\']([^\']+)[\']\s*\)/', $content, $matches);
        if (!empty($matches[1])) {
            foreach ($matches[1] as $match) {
                $keys[$match] = '';
            }
        }
        
        // Match __("...")
        preg_match_all('/__\(\s*[\"]([^\"]+)[\"]\s*\)/', $content, $matches);
        if (!empty($matches[1])) {
            foreach ($matches[1] as $match) {
                $keys[$match] = '';
            }
        }
    }
}

$enPath = __DIR__ . '/lang/en.json';
$arPath = __DIR__ . '/lang/ar.json';

if (!is_dir(__DIR__ . '/lang')) {
    mkdir(__DIR__ . '/lang', 0777, true);
}

$en = file_exists($enPath) ? json_decode(file_get_contents($enPath), true) : [];
if (!is_array($en)) $en = [];

$ar = file_exists($arPath) ? json_decode(file_get_contents($arPath), true) : [];
if (!is_array($ar)) $ar = [];

foreach ($keys as $k => $v) {
    if (!isset($en[$k])) {
        $en[$k] = $k;
    }
    if (!isset($ar[$k])) {
        $ar[$k] = $k;
    }
}

file_put_contents($enPath, json_encode($en, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
file_put_contents($arPath, json_encode($ar, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo 'Extracted ' . count($keys) . ' keys.' . PHP_EOL;
