<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use App\Models\Subscriber;

class LemonWebhookController
{
    public function handle(Request $request)
    {
        $event = $request->input('meta.event_name');
        $data  = $request->input('data');

        match ($event) {
            'subscription_created' => $this->subscriptionCreated($data),
            'subscription_updated' => $this->subscriptionUpdated($data),
            'subscription_cancelled',
            'subscription_expired' => $this->subscriptionCancelled($data),
            default => null,
        };

        return response()->json(['ok' => true]);
    }

    protected function subscriptionCreated(array $data)
    {
        $email = $data['attributes']['user_email'];

        $user = User::where('email', $email)->first();

        if (! $user) {
            return;
        }

        Subscriber::updateOrCreate(
            ['user_id' => $user->id],
            [
                'lemon_subscription_id' => $data['id'],
                'lemon_variant_id'      => $data['attributes']['variant_id'],
                'active'                => true,
                'ends_at'               => $data['attributes']['ends_at'],
            ]
        );

        Cache::forget("lemon:subscription:user:{$user->id}");
    }

    protected function subscriptionUpdated(array $data)
    {
        $this->subscriptionCreated($data);
    }

    protected function subscriptionCancelled(array $data)
    {
        $subscriptionId = $data['id'];

        $subscriber = Subscriber::where('lemon_subscription_id', $subscriptionId)->first();

        if (! $subscriber) {
            return;
        }

        $subscriber->update([
            'active'  => false,
            'ends_at' => $data['attributes']['ends_at'],
        ]);

        Cache::forget("lemon:subscription:user:{$subscriber->user_id}");
    }
}
