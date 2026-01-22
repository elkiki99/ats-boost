<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Actions\Lemon\SyncSubscription;

class LemonWebhookController
{
    public function handle(Request $request, SyncSubscription $sync)
    {
        $event = $request->input('meta.event_name');
        $data  = $request->input('data');

        if (! is_array($data)) {
            return response()->json(['ok' => true]);
        }

        match ($event) {
            'subscription_created',
            'subscription_updated',
            'subscription_cancelled',
            'subscription_expired',
            'subscription_payment_failed',
            'subscription_payment_success'
                => $sync->handle($data),

            default => null,
        };

        return response()->json(['ok' => true]);
    }
}