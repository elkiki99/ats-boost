<?php

namespace App\Http\Controllers;

use App\Actions\MercadoPago\SyncSubscription;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handle(Request $request, SyncSubscription $sync): JsonResponse
    {
        Log::info('Mercado Pago webhook payload', $request->all());

        $topic = $request->input('type')
            ?? $request->input('topic');

        // Caso 1: data.id
        $resourceId = $request->input('data.id');

        // Caso 2: resource = URL completa
        if (!$resourceId && $request->filled('resource')) {
            $resourceId = basename($request->input('resource'));
        }

        if (!$topic || !$resourceId) {
            Log::warning('Mercado Pago: Missing topic or resource ID', [
                'payload' => $request->all(),
            ]);

            return response()->json(['ok' => false], 400);
        }

        Log::info('Mercado Pago webhook received', [
            'topic' => $topic,
            'resource_id' => $resourceId,
        ]);

        match ($topic) {
            'subscription_preapproval' => $sync->handle([
                'id' => $resourceId,
                'type' => 'subscription_preapproval',
            ]),
            'subscription_authorized_payment' => $sync->handle([
                'id' => $resourceId,
                'type' => 'subscription_authorized_payment',
            ]),
            default => Log::info('Mercado Pago: Unhandled webhook topic', [
                'topic' => $topic,
            ]),
        };

        return response()->json(['ok' => true]);
    }
}
