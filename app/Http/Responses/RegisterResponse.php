<?php

namespace App\Http\Responses;

use Illuminate\Http\RedirectResponse;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

class RegisterResponse implements RegisterResponseContract
{
    public function toResponse($request): RedirectResponse
    {
        if ($planId = $request->session()->pull('checkout_plan_id')) {
            return redirect()->route('checkout.start', ['variant' => $planId]);
        }

        return redirect()->intended(config('fortify.home'));
    }
}
