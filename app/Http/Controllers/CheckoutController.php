<?php

namespace App\Http\Controllers;

class CheckoutController extends Controller
{
    public function start(string $variant)
    {
        session(['checkout_variant' => $variant]);

        if (! auth()->check()) {
            return redirect()->guest(route('login'));
        }

        $user = auth()->user();

        if ($user->subscriber?->hasAccess()) {
            return redirect()->route('subscriptions.edit');
        }

        return redirect()->away(
            'https://www.mercadopago.com.uy/subscriptions/checkout?preapproval_plan_id=' . $variant
        );
    }
}