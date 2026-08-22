<?php

namespace App\Http\Responses;

use Illuminate\Http\RedirectResponse;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request): RedirectResponse
    {
        // Quien llegó al login desde un botón de precios vuelve a ese checkout.
        if ($planId = $request->session()->pull('checkout_plan_id')) {
            return redirect()->route('checkout.start', ['variant' => $planId]);
        }

        return redirect()->intended(config('fortify.home'));
    }
}
