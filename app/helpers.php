<?php

/**
 * ================================================================
 * Global Application Helpers
 * Auto-loaded by composer via "autoload.files" in composer.json
 * ================================================================
 */

if (! function_exists('tenant_setting')) {
    /**
     * Retrieve a tenant/system setting value by key with an optional default.
     * Values are cached via Cache::rememberForever inside Setting::get().
     *
     * Usage:  tenant_setting('app_name', 'الميزان')
     */
    function tenant_setting(string $key, mixed $default = null): mixed
    {
        return \App\Models\Setting::get($key, $default);
    }
}

if (! function_exists('firm_name')) {
    /**
     * Return the configured firm / office name.
     * Falls back to APP_NAME env → 'الميزان' as last resort.
     *
     * Usage (Blade):   {{ firm_name() }}
     * Usage (PHP):     $subject = firm_name() . ' — New Case';
     */
    function firm_name(): string
    {
        return tenant_setting('app_name', config('app.name', 'الميزان'));
    }
}

if (! function_exists('firm_country')) {
    /**
     * Return the configured firm country code (EG, SA, AE, OM, etc.).
     * Respects demo mode session if active.
     */
    function firm_country(): string
    {
        return session('demo_country') ?? tenant_setting('firm_country', 'EG');
    }
}

if (! function_exists('format_currency')) {
    /**
     * Format a number with the appropriate currency symbol.
     * Uses session('demo_currency') if set, otherwise falls back to tenant_setting or firm country.
     */
    function format_currency($amount): string
    {
        $currency = session('demo_currency', tenant_setting('currency', null));
        if (!$currency) {
            $country = firm_country();
            $countryCurrencyMap = [
                'EG' => 'ج.م.',
                'SA' => 'ر.س.',
                'AE' => 'د.إ.',
                'OM' => 'ر.ع.',
                'KW' => 'د.ك.',
                'BH' => 'د.ب.',
                'QA' => 'ر.ق.',
                'JO' => 'د.أ.',
                'MA' => 'د.م.',
                'LY' => 'د.ل.',
                'IQ' => 'د.ع.',
                'LB' => 'ل.ل.',
                'YE' => 'ر.ي.',
                'SY' => 'ل.س.',
                'US' => '$',
                'GB' => '£',
                'ES' => '€',
            ];
            $currency = $countryCurrencyMap[$country] ?? 'ج.م.';
        }
        return number_format((float) $amount) . ' ' . $currency;
    }
}

if (! function_exists('app_locale')) {
    /**
     * Return the currently active locale code.
     * Priority: session → DB setting → config default.
     */
    function app_locale(): string
    {
        return app()->getLocale();
    }
}

if (! function_exists('is_rtl')) {
    /**
     * Return true when the current locale is RTL.
     */
    function is_rtl(): bool
    {
        return app_locale() === 'ar';
    }
}
