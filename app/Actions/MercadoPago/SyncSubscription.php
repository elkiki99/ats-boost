<?php

namespace App\Actions\MercadoPago;

use App\Models\Subscriber;
use App\Services\MercadoPagoService;
use Illuminate\Support\Facades\Log;

class SyncSubscription
{
    public function __construct(private MercadoPagoService $mercadoPago) {}

    public function handle(array $data): void
    {
        if (!isset($data['id'])) {
            Log::warning('Mercado Pago: Missing subscription ID in webhook data');

            return;
        }

        $subscriptionId = $data['id'];

        try {
            // Fetch latest subscription data from Mercado Pago API
            $subscriptionData = $this->mercadoPago->getSubscription($subscriptionId);

            // Use updateOrCreate to sync - crea si no existe, actualiza si existe
            Subscriber::updateOrCreate(
                ['mp_subscription_id' => $subscriptionId],
                [
                    'status' => $subscriptionData['status'] ?? 'pending',
                    'active' => in_array($subscriptionData['status'] ?? '', ['authorized', 'active']),
                    'renews_at' => isset($subscriptionData['next_billing_date'])
                        ? \Carbon\Carbon::parse($subscriptionData['next_billing_date'])
                        : null,
                    'payer_email' => $subscriptionData['payer']['email'] ?? null,
                    'metadata' => $subscriptionData,
                ]
            );

            Log::info('Mercado Pago: Subscription synced successfully', [
                'subscription_id' => $subscriptionId,
                'status' => $subscriptionData['status'] ?? 'unknown',
            ]);
        } catch (\Exception $e) {
            Log::error('Mercado Pago: Error fetching subscription data', [
                'subscription_id' => $subscriptionId,
                'error' => $e->getMessage(),
            ]);
        }
    }
}