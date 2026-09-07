<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    private const SUPPORTED = ['ar', 'en'];

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Session takes highest priority
        $locale = session('app_locale');

        // 2. Fall back to tenant DB setting
        if (!$locale && function_exists('tenant_setting')) {
            $locale = tenant_setting('app_locale', null);
        }

        // 3. Fall back to config default ('ar')
        if (!in_array($locale, self::SUPPORTED, true)) {
            $locale = config('app.locale', 'ar');
        }

        App::setLocale($locale);
        
        return $next($request);
    }
}
