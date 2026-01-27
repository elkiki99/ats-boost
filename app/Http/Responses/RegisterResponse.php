<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

class RegisterResponse implements RegisterResponseContract
{
    public function toResponse($request)
    {
        if ($variant = $request->session()->pull('checkout_variant')) {
            return redirect()->route('checkout.start', $variant);
        }

        return redirect(config('fortify.home'));
    }
}
