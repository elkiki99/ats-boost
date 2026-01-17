<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LemonWebhookController
{
    public function handle(Request $request)
    {
        $event = $request->input('meta.event_name');
        $data  = $request->input('data');

        if (! is_array($data)) {
            return response()->json(['ok' => true]);
        }

        match ($event) {
            'subscription_created'  => $this->subscriptionCreated($data),
            'subscription_updated'  => $this->subscriptionUpdated($data),
            'subscription_cancelled' => $this->subscriptionCancelled($data),
            'subscription_expired'   => $this->subscriptionCancelled($data),
            default                  => null,
        };

        return response()->json(['ok' => true]);
    }

    protected function subscriptionCreated(array $data)
    {
        $email = data_get($data, 'attributes.user_email');

        if (! $email) {
            return;
        }

        $user = User::where('email', $email)->first();

        if (! $user) {
            return;
        }

        Subscriber::updateOrCreate(
            ['user_id' => $user->id],
            [
                'lemon_subscription_id' => data_get($data, 'id'),
                'lemon_variant_id'      => data_get($data, 'attributes.variant_id'),
                'active'                => true,
                'ends_at'               => data_get($data, 'attributes.ends_at'),
            ]
        );

        Cache::forget("lemon:subscription:user:{$user->id}");
    }

    protected function subscriptionUpdated(array $data)
    {
        $subscriptionId = data_get($data, 'id');
        $status = data_get($data, 'attributes.status');

        $subscriber = Subscriber::where(
            'lemon_subscription_id',
            $subscriptionId
        )->first();

        if (! $subscriber) {
            return;
        }

        $subscriber->update([
            'active'  => $this->isActiveStatus($status),
            'ends_at' => data_get($data, 'attributes.ends_at'),
        ]);

        Cache::forget("lemon:subscription:user:{$subscriber->user_id}");
    }

    protected function subscriptionCancelled(array $data)
    {
        $subscriptionId = data_get($data, 'id');

        $subscriber = Subscriber::where('lemon_subscription_id', $subscriptionId)->first();

        if (! $subscriber) {
            return;
        }

        $subscriber->update([
            'active'  => false,
            'ends_at' => data_get($data, 'attributes.ends_at'),
        ]);

        Cache::forget("lemon:subscription:user:{$subscriber->user_id}");
    }

    protected function isActiveStatus(?string $status): bool
    {
        return in_array($status, ['on_trial', 'active', 'past_due'], true);
    }
}
