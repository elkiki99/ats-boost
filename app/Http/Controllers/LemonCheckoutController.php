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
            return redirect()->route('pricing');
        }

        return redirect()->away($checkoutUrl);
    }
}
