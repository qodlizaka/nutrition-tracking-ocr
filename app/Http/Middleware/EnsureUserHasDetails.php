<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasDetails
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return $next($request);
        }

        if (! $user->detail) {

            if ($request->routeIs('settings.personal-info') || $request->routeIs('settings.personal-info.update')) {
                return $next($request);
            }

            return redirect()->route('settings.personal-info');
        }

        return $next($request);
    }
}
