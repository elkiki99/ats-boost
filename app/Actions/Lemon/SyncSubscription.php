<?php

namespace App\Actions\Lemon;

use App\Models\Subscriber;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class SyncSubscription
{
    public function handle(array $data): void
    {
        $attributes = $data['attributes'] ?? [];

        $email =
            $attributes['customer_email']
            ?? $attributes['user_email']
            ?? null;

        if (! $email) {
            logger()->warning('Lemon webhook without email', $data);
            return;
        }

        $variantId =
            $attributes['variant_id']
            ?? data_get($data, 'relationships.variant.data.id');

        if (! $variantId) {
            logger()->warning('Lemon webhook without variant_id', $data);
            return;
        }

        $status = $attributes['status'] ?? 'active';
        $endsAt = $attributes['ends_at'] ?? null;

        $user = User::where('email', $email)->first();

        Subscriber::updateOrCreate(
            ['lemon_subscription_id' => (string) $data['id']],
            [
                'user_id'          => $user?->id,
                'lemon_variant_id' => (string) $variantId,
                'active'           => $this->isActive($status),
                'ends_at'          => $endsAt,
            ]
        );

        if ($user) {
            Cache::forget("lemon:subscription:user:{$user->id}");
        }
    }

    protected function isActive(?string $status): bool
    {
        return in_array($status, [
            'on_trial',
            'active',
            'past_due',
        ], true);
    }
}
