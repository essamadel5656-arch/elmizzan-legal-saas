<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Only allow users with role = 'admin' to pass through.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'هذه الصفحة متاحة للمدير فقط.');
        }

        return $next($request);
    }
}
