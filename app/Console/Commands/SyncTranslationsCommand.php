<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SyncTranslationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-translations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scan Blade files, wrap raw Arabic strings in __(), extract all translations, and sync to lang/en.json';

    /**
     * Common legal dictionary for auto-translation of harvested keys.
     */
    protected $dictionary = [
        "إضافة قضية جديدة" => "Add New Case",
        "المحكمة" => "Court",
        "درجة التقاضي" => "Court Level",
        "الكل" => "All",
        "مفتوحة" => "Open",
        "متداولة" => "In Progress",
        "مؤجلة" => "Postponed",
        "محجوزة للحكم" => "Reserved for Judgment",
        "منتهية" => "Completed",
        "مستأنفة" => "Appealed",
        "محفوظة" => "Archived",
        "معلقة" => "Pending",
        "محامي رئيسي" => "Lead Lawyer",
        "مساعد" => "Assistant",
        "مستشار" => "Consultant",
        "غير محدد" => "Not Specified",
        "أدخل تفاصيل القضية..." => "Enter case details...",
        "أدخل الإجراء السابق..." => "Enter previous procedure...",
        "أدخل القرار النهائي..." => "Enter final decision...",
        "أدخل ملاحظات إضافية..." => "Enter additional notes...",
        "تعديل" => "Edit",
        "حذف" => "Delete",
        // ... (Many more could be added, but we'll use a dynamic fallback)
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Scanning Blade files in resources/views...");
        
        $viewsPath = resource_path('views');
        $bladeFiles = File::allFiles($viewsPath);
        $langFile = base_path('lang/en.json');
        
        $translations = [];
        if (File::exists($langFile)) {
            $jsonContent = File::get($langFile);
            $parsed = json_decode($jsonContent, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $translations = $parsed;
            } else {
                $this->error("lang/en.json contains invalid JSON. Aborting to prevent data loss.");
                return 1;
            }
        }

        $allKeys = [];
        $modifiedFilesCount = 0;

        foreach ($bladeFiles as $file) {
            if ($file->getExtension() !== 'php' || !str_ends_with($file->getFilename(), '.blade.php')) {
                continue;
            }
            
            $content = File::get($file->getPathname());
            $originalContent = $content;

            // Step 1: Wrap raw Arabic strings (Text between HTML tags)
            // Match > [Arabic text with spaces/punctuation] <
            $content = preg_replace_callback(
                '/(?<=>)([^<{]*?[\p{Arabic}]+[^<}]*?)(?=<)/u',
                function ($matches) {
                    $text = trim($matches[1]);
                    // If it contains blade echoes or directives, skip
                    if (empty($text) || str_contains($text, '{{') || str_contains($text, '@')) {
                        return $matches[0];
                    }
                    
                    // Replace the text inside the original match, preserving surrounding whitespace
                    $replaced = str_replace($text, '{{ __(\'' . str_replace("'", "\'", $text) . '\') }}', $matches[0]);
                    return $replaced;
                },
                $content
            );

            // Step 2: Wrap raw Arabic strings inside attributes (placeholder, title, alt, etc)
            $content = preg_replace_callback(
                '/(placeholder|title|alt|label|value)=["\']([^"\']*?[\p{Arabic}]+[^"\']*?)["\']/u',
                function ($matches) {
                    $attr = $matches[1];
                    $val = trim($matches[2]);
                    
                    // If it's already a blade directive, skip
                    if (str_contains($val, '{{') || str_contains($val, '@')) {
                        return $matches[0];
                    }
                    
                    return $attr . '="{{ __(\'' . str_replace("'", "\'", $val) . '\') }}"';
                },
                $content
            );

            if ($content !== $originalContent) {
                File::put($file->getPathname(), $content);
                $modifiedFilesCount++;
            }

            // Step 3: Harvest all __('...') and @lang('...') keys from the (now updated) content
            preg_match_all('/__\(\s*[\'"]([^\'"]+?)[\'"]\s*\)/u', $content, $matches1);
            preg_match_all('/@lang\(\s*[\'"]([^\'"]+?)[\'"]\s*\)/u', $content, $matches2);
            
            $keys = array_merge($matches1[1], $matches2[1]);
            foreach ($keys as $key) {
                // Normalize string keys (trim whitespace and colons to prevent mismatches)
                $key = trim($key);
                $key = trim($key, ':');
                
                if (!empty($key) && preg_match('/[\p{Arabic}]/u', $key)) {
                    $allKeys[] = $key;
                }
            }
        }

        $allKeys = array_unique($allKeys);
        $addedCount = 0;

        $this->info("Found " . count($allKeys) . " unique Arabic keys across " . count($bladeFiles) . " Blade files.");

        // Step 4: Compare with lang/en.json and add missing keys
        foreach ($allKeys as $key) {
            if (!isset($translations[$key])) {
                // Add paired with its accurate, professional English legal translation (or fallback to the Arabic key to be translated later)
                $translated = $this->dictionary[$key] ?? $key;
                $translations[$key] = $translated;
                $addedCount++;
            }
        }

        // Step 5: Save lang/en.json formatted cleanly
        $jsonOutput = json_encode($translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        File::put($langFile, $jsonOutput);

        // Verify JSON syntax
        json_decode($jsonOutput);
        if (json_last_error() === JSON_ERROR_NONE) {
            $this->info("Successfully synced translations. Added {$addedCount} new keys.");
            $this->info("Modified {$modifiedFilesCount} Blade files to enclose raw Arabic text.");
            $this->info("lang/en.json compiled with ZERO JSON syntax errors.");
        } else {
            $this->error("JSON formatting error occurred during save: " . json_last_error_msg());
            return 1;
        }

        return 0;
    }
}
