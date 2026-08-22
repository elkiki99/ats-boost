<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Exige suscripción vigente.
 *
 * Reemplaza a SubscribedMiddleware y EnsureSubscriptionIsValid, que hacían lo
 * mismo con criterios distintos: uno miraba `ends_at` sin mirar el estado y el
 * otro aceptaba suscripciones en `paused`. Con dos definiciones conviviendo,
 * qué acceso tenía el usuario dependía de por qué ruta hubiera entrado.
 */
class EnsureSubscribed
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user === null) {
            return redirect()->guest(route('login'));
        }

        if (! $user->isSubscribed()) {
            // El aviso viaja como flash y no como sesión persistente: la
            // versión anterior dejaba `subscription_required` pegado hasta que
            // alguien pasara por la pantalla de suscripciones, y el toast
            // reaparecía días después.
            return redirect()
                ->route('subscriptions.edit')
                ->with('subscription_required', true);
        }

        return $next($request);
    }
}
