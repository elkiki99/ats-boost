<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSubscriptionIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (! $user) {
            abort(401);
        }

        // Get the latest active or paused subscription
        $subscription = $user->subscribers()
            ->whereIn('status', ['authorized', 'active', 'paused'])
            ->latest()
            ->first();

        if (! $subscription || ! $subscription->hasAccess()) {
            abort(403, __('No tienes una suscripción activa.'));
        }

        return $next($request);
    }
}
