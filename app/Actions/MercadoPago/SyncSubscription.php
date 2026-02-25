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
        if (!isset($data['id'])) {
            Log::warning('Mercado Pago: Missing subscription ID');
            return;
        }

        $subscriptionId = $data['id'];

        try {

            $subscriptionData = $this->mercadoPago->getSubscription($subscriptionId);

            $status = $subscriptionData['status'] ?? 'pending';

            $nextPaymentDate = isset($subscriptionData['next_payment_date'])
                ? Carbon::parse($subscriptionData['next_payment_date'])
                : null;

            /*
             |--------------------------------------------------------------------------
             | Calcular trial manualmente desde auto_recurring
             |--------------------------------------------------------------------------
             */

            $trialEndsAt = null;
            $autoRecurring = $subscriptionData['auto_recurring'] ?? null;

            if (
                isset($autoRecurring['free_trial']['frequency']) &&
                isset($autoRecurring['free_trial']['frequency_type']) &&
                isset($autoRecurring['start_date'])
            ) {
                $startDate = Carbon::parse($autoRecurring['start_date']);
                $frequency = $autoRecurring['free_trial']['frequency'];
                $type = $autoRecurring['free_trial']['frequency_type'];

                $trialEndsAt = match ($type) {
                    'days' => $startDate->copy()->addDays($frequency),
                    'months' => $startDate->copy()->addMonths($frequency),
                    'years' => $startDate->copy()->addYears($frequency),
                    default => null,
                };
            }

            /*
             |--------------------------------------------------------------------------
             | Definir ends_at correctamente
             |--------------------------------------------------------------------------
             */

            if ($trialEndsAt && now()->lt($trialEndsAt)) {
                // Está en trial
                $endsAt = $trialEndsAt;
            } else {
                // Ya pagó o no tiene trial
                $endsAt = $nextPaymentDate;
            }

            $subscriber = Subscriber::where('mp_subscription_id', $subscriptionId)->first();

            if (! $subscriber) {

                // Solo podemos crear si hay usuario autenticado
                if (! auth()->check()) {
                    Log::warning('Mercado Pago: Cannot create subscription without authenticated user', [
                        'subscription_id' => $subscriptionId,
                    ]);
                    return;
                }

                $subscriber = Subscriber::create([
                    'user_id' => auth()->id(),
                    'mp_subscription_id' => $subscriptionId,
                    'mp_plan_id' => $subscriptionData['preapproval_plan_id'] ?? null,
                    'status' => $status,
                    'active' => in_array($status, ['authorized', 'active']),
                    'renews_at' => $nextPaymentDate,
                    'trial_ends_at' => $trialEndsAt,
                    'ends_at' => $endsAt,
                    'payer_email' => $subscriptionData['payer_email'] ?? null,
                    'metadata' => $subscriptionData,
                ]);

            } else {

                // Update SIN tocar user_id
                $subscriber->update([
                    'mp_plan_id' => $subscriptionData['preapproval_plan_id'] ?? null,
                    'status' => $status,
                    'active' => in_array($status, ['authorized', 'active']),
                    'renews_at' => $nextPaymentDate,
                    'trial_ends_at' => $trialEndsAt,
                    'ends_at' => $endsAt,
                    'payer_email' => $subscriptionData['payer_email'] ?? null,
                    'metadata' => $subscriptionData,
                ]);
            }

            Log::info('Mercado Pago: Subscription synced successfully', [
                'subscription_id' => $subscriptionId,
                'user_id' => auth()->id(),
                'status' => $status,
            ]);

        } catch (\Throwable $e) {

            Log::error('Mercado Pago: Error syncing subscription', [
                'subscription_id' => $subscriptionId,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
