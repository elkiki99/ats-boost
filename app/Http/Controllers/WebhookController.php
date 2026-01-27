<?php

namespace App\Http\Controllers;

use App\Actions\MercadoPago\SyncSubscription;
use App\Http\Controllers\Controller;
use App\Services\MercadoPagoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handle(Request $request, SyncSubscription $sync, MercadoPagoService $mercadoPago): JsonResponse
    {
        // Mercado Pago sends notification data in different formats
        $topic = $request->input('type') ?? $request->input('topic');
        $resourceId = $request->input('id') ?? $request->input('resource');

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

        // Handle subscription-related events
        match ($topic) {
            'subscription_preapproval' => $this->handleSubscriptionPreapproval($resourceId, $sync),
            'subscription_authorized_payment' => $this->handleSubscriptionPayment($resourceId, $sync),
            default => Log::info('Mercado Pago: Unhandled webhook topic', ['topic' => $topic]),
        };

        return response()->json(['ok' => true]);
    }

    private function handleSubscriptionPreapproval(string $subscriptionId, SyncSubscription $sync): void
    {
        try {
            $sync->handle([
                'id' => $subscriptionId,
                'type' => 'subscription_preapproval',
            ]);
        } catch (\Exception $e) {
            Log::error('Mercado Pago: Error syncing subscription preapproval', [
                'subscription_id' => $subscriptionId,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function handleSubscriptionPayment(string $subscriptionId, SyncSubscription $sync): void
    {
        try {
            $sync->handle([
                'id' => $subscriptionId,
                'type' => 'subscription_authorized_payment',
            ]);
        } catch (\Exception $e) {
            Log::error('Mercado Pago: Error syncing subscription payment', [
                'subscription_id' => $subscriptionId,
                'error' => $e->getMessage(),
            ]);
        }
    }
}