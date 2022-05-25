<?php

namespace App\Http\Middleware;

use Closure;

class AssignGuard
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        // Pre-Middleware Action
        // $response = $next($request);

        // Post-Middleware Action
        // return $response;

        if($guard != null) {
            auth()->shouldUse($guard);
        }

        return $next($request);
    }
}
