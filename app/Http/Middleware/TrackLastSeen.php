<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TrackLastSeen
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            // Update last_seen_at only if it's null or older than 2 minutes
            if (!$user->last_seen_at || $user->last_seen_at->lt(now()->subMinutes(2))) {
                DB::table('users')->where('id', $user->id)->update([
                    'last_seen_at' => now(),
                ]);
            }
        }

        return $next($request);
    }
}
