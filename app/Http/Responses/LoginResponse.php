<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        if ($variant = $request->session()->pull('checkout_variant')) {
            return redirect()->route('checkout', $variant);
        }

        return redirect()->intended(config('fortify.home'));
    }
}