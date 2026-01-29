<?php

namespace App\Actions\MercadoPago;

use App\Models\Subscriber;
use App\Services\MercadoPagoService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SyncSubscription
{
    public function __construct(
        private MercadoPagoService $mercadoPago
    ) {}

    public function handle(array $data): void
    {
        if (! isset($data['id'])) {
            Log::warning('Mercado Pago: Missing subscription ID');

            return;
        }

        $subscriptionId = $data['id'];

        try {
            // 1️⃣ Traemos la suscripción desde Mercado Pago
            $subscriptionData = $this->mercadoPago->getSubscription($subscriptionId);

            // 3️⃣ Sincronizamos en DB (idempotente)
            Subscriber::updateOrCreate(
                ['mp_subscription_id' => $subscriptionId],
                [
                    'user_id' => auth()->id(),
                    'mp_plan_id' => $subscriptionData['preapproval_plan_id'] ?? null,
                    'status' => $subscriptionData['status'] ?? 'pending',
                    'active' => in_array(
                        $subscriptionData['status'] ?? '',
                        ['authorized', 'active']
                    ),
                    'renews_at' => isset($subscriptionData['next_payment_date'])
                        ? Carbon::parse($subscriptionData['next_payment_date'])
                        : null,
                    'payer_email' => $subscriptionData['payer_email'] ?? null,
                    'metadata' => $subscriptionData,
                ]
            );

            Log::info('Mercado Pago: Subscription synced successfully', [
                'subscription_id' => $subscriptionId,
                'user_id' => auth()->user()->id,
                'status' => $subscriptionData['status'] ?? 'unknown',
            ]);
        } catch (\Throwable $e) {
            Log::error('Mercado Pago: Error syncing subscription', [
                'subscription_id' => $subscriptionId,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
