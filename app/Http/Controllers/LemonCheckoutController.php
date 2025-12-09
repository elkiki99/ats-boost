<?php

namespace App\Http\Controllers;

use App\Services\LemonSqueezyService;
use Illuminate\Support\Facades\Auth;

class LemonCheckoutController extends Controller
{
    public function create(LemonSqueezyService $lemon, string $variantId)
    {
        $user = Auth::user();

        $checkoutUrl = $lemon->createCheckout($variantId, $user->email);

        if (!$checkoutUrl) {
            // Redirige a pricing si hay error
            return redirect()->route('pricing');
        }

        // Redirige al checkout de Lemon Squeezy
        return redirect()->away($checkoutUrl);
    }
}
