<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SubscribedMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $subscription = auth()->user()?->subscriber;

        if (! $subscription || ! $subscription->hasAccess()) {
            abort(403);
        }

        return $next($request);
    }
}
