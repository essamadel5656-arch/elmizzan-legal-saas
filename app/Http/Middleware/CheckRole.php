<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
public function handle(Request $request, Closure $next): Response
{
    if (!auth()->check()) {
        return redirect('/login');
    }

    $user = auth()->user();

    if ($user->role === 'admin') {
        return $next($request);
    }

    if ($user->role === 'lawyer') {
        if ($request->is('lawyers/' . $user->lawyer_id) && $request->isMethod('get')) {
            return $next($request);
        }
        if ($request->is('lawyers*')) {
            return redirect('/cases')->with('error', 'ليس لديك صلاحية');
        }
        return $next($request);
    }

    return redirect('/')->with('error', 'ليس لديك صلاحية');
}
}
