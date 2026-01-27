<?php

namespace App\Http\Controllers;

use App\Actions\MercadoPago\SyncSubscription;
use App\Http\Controllers\Controller;
use App\Services\MercadoPagoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handle(
        Request $request,
        SyncSubscription $sync,
        MercadoPagoService $mercadoPago
    ): JsonResponse {
        // Log completo para debug (clave con MP)
        Log::info('MP webhook payload', $request->all());

        $type = $request->input('type');        // payment | subscription_preapproval
        $entity = $request->input('entity');    // preapproval | payment
        $resourceId = $request->input('data.id');

        if (! $type || ! $resourceId) {
            Log::warning('Mercado Pago: Missing type or resource ID');

            return response()->json(['ok' => false], 400);
        }

        match ($type) {
            // Suscripción creada / actualizada
            'subscription_preapproval' => $this->syncSubscription($resourceId, $sync),

            // Pago realizado → hay que buscar el preapproval_id
            'payment' => $this->handlePayment($resourceId, $sync),

            default => Log::info('Mercado Pago: Unhandled webhook type', [
                'type' => $type,
                'entity' => $entity,
            ]),
        };

        return response()->json(['ok' => true]);
    }

    private function syncSubscription(string $subscriptionId, SyncSubscription $sync): void
    {
        try {
            $sync->handle(['id' => $subscriptionId]);
        } catch (\Throwable $e) {
            Log::error('MP: Error syncing subscription', [
                'subscription_id' => $subscriptionId,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function handlePayment(string $paymentId, SyncSubscription $sync): void
    {
        try {
            $payment = Http::withToken(config('services.mercadopago.access_token'))
                ->get("https://api.mercadopago.com/v1/payments/{$paymentId}")
                ->throw()
                ->json();

            if (! isset($payment['preapproval_id'])) {
                Log::warning('MP: Payment without preapproval_id', [
                    'payment_id' => $paymentId,
                ]);
                return;
            }

            // 🔥 ESTE ES EL ID REAL DE LA SUSCRIPCIÓN
            $sync->handle([
                'id' => $payment['preapproval_id'],
            ]);
        } catch (\Throwable $e) {
            Log::error('MP: Error handling payment webhook', [
                'payment_id' => $paymentId,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
