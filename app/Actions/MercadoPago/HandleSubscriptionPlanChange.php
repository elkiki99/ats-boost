<?php

namespace App\Actions\MercadoPago;

use App\Models\Subscriber;
use App\Models\User;
use App\Services\MercadoPagoService;
use Illuminate\Support\Facades\Log;

class HandleSubscriptionPlanChange
{
    public function __construct(
        protected MercadoPagoService $mercadoPagoService
    ) {}

    /**
     * Handle subscription plan change safely.
     * Only cancels the old subscription after the new one is confirmed.
     */
    public function handle(User $user, string $newSubscriptionId, string $oldSubscriptionId): bool
    {
        try {
            // Verify the new subscription exists and is active
            $newSubscription = Subscriber::where('user_id', $user->id)
                ->where('mp_subscription_id', $newSubscriptionId)
                ->first();

            if (! $newSubscription) {
                Log::warning('Plan change: New subscription not found', [
                    'user_id' => $user->id,
                    'new_subscription_id' => $newSubscriptionId,
                ]);

                return false;
            }

            // Verify it's authorized/active (not pending)
            if (! in_array($newSubscription->status, ['authorized', 'active'])) {
                Log::warning('Plan change: New subscription not yet active', [
                    'user_id' => $user->id,
                    'new_subscription_id' => $newSubscriptionId,
                    'status' => $newSubscription->status,
                ]);

                return false;
            }

            // Now safe to cancel the old subscription
            $this->mercadoPagoService->cancelSubscription($oldSubscriptionId);

            // Sync the old subscription to mark it as cancelled
            app(SyncSubscription::class)->handle([
                'id' => $oldSubscriptionId,
                'source' => 'plan_change_completed',
            ]);

            Log::info('Plan change completed successfully', [
                'user_id' => $user->id,
                'old_subscription_id' => $oldSubscriptionId,
                'new_subscription_id' => $newSubscriptionId,
            ]);

            return true;
        } catch (\Throwable $e) {
            Log::error('Plan change failed when cancelling old subscription', [
                'user_id' => $user->id,
                'old_subscription_id' => $oldSubscriptionId,
                'new_subscription_id' => $newSubscriptionId,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }
}
