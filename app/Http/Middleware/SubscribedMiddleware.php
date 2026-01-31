<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Flux\Flux;

class SubscribedMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
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
            session(['subscription_required' => true]);
            return redirect()->route('subscriptions.edit');
        }

        return $next($request);
    }
}
