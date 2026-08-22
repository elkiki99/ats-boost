<?php

namespace App\Http\Controllers;

use App\Http\Requests\StartCheckoutRequest;
use Illuminate\Http\RedirectResponse;

class CheckoutController extends Controller
{
    public function start(StartCheckoutRequest $request): RedirectResponse
    {
        $planId = $request->planId();

        if (! $request->user()) {
            // El plan elegido sobrevive al login para que el usuario vuelva al
            // checkout que quería y no a la grilla de precios.
            session(['checkout_plan_id' => $planId]);

            return redirect()->guest(route('login'));
        }

        if ($request->user()->isSubscribed()) {
            return redirect()->route('subscriptions.edit');
        }

        session()->forget('checkout_plan_id');

        return redirect()->away(
            'https://www.mercadopago.com.uy/subscriptions/checkout?preapproval_plan_id='.urlencode($planId)
        );
    }
}
