<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class LemonSqueezyService
{
    protected string $apiUrl = 'https://api.lemonsqueezy.com/v1';

    public function __construct(
        protected string $apiKey = '',
        protected string $storeId = ''
    ) {
        $this->apiKey = config('services.lemonsqueezy.key');
        $this->storeId = config('services.lemonsqueezy.store');
    }

    public function createCheckout(string $variantId, string $email)
    {
        $payload = [
            "data" => [
                "type" => "checkouts",
                "attributes" => [
                    "checkout_data" => [
                        "email" => $email,
                    ]
                ],
                "relationships" => [
                    "store" => [
                        "data" => [
                            "type" => "stores",
                            "id" => $this->storeId
                        ]
                    ],
                    "variant" => [
                        "data" => [
                            "type" => "variants",
                            "id" => $variantId
                        ]
                    ]
                ]
            ]
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Accept' => 'application/json',
        ])->post("{$this->apiUrl}/checkouts", $payload);

        $data = $response->json();

        // Si algo falla, retornamos null
        if ($response->failed() || empty($data['data']['attributes']['url'])) {
            return null;
        }

        // Retornamos la URL del checkout
        return $data['data']['attributes']['url'];
    }


    public function getSubscriptionsByEmail(string $email)
    {
        $response = Http::withHeaders([
            'Accept'        => 'application/vnd.api+json',
            'Content-Type'  => 'application/vnd.api+json',
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->get("{$this->apiUrl}/subscriptions");

        if ($response->failed()) {
            return [];
        }

        $allSubscriptions = $response->json()['data'] ?? [];

        return collect($allSubscriptions)
            ->filter(fn($sub) => $sub['attributes']['user_email'] === $email)
            ->values()
            ->all();
    }

    public function getActualSubscription(string $email)
    {
        return cache()->remember("subsription_{$email}", now()->addMinutes(10), function () use ($email) {
            $response = Http::withHeaders([
                'Accept'        => 'application/vnd.api+json',
                'Content-Type'  => 'application/vnd.api+json',
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->get("{$this->apiUrl}/subscriptions");

            if ($response->failed()) {
                return null;
            }

            $allSubscriptions = $response->json()['data'] ?? [];

            $validStatuses = ['on_trial', 'active', 'past_due'];

            return collect($allSubscriptions)
                ->filter(
                    fn($sub) =>
                    $sub['attributes']['user_email'] === $email &&
                        in_array($sub['attributes']['status'], $validStatuses)
                )
                ->sortByDesc(fn($sub) => $sub['attributes']['created_at'])
                ->first(); // 👈 devuelve una sola
        });
    }

    public function cancelSubscription(string $subscriptionId)
    {
        $response = Http::withHeaders([
            'Accept' => 'application/vnd.api+json',
            'Content-Type' => 'application/vnd.api+json',
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->delete("{$this->apiUrl}/subscriptions/{$subscriptionId}");

        if ($response->failed()) {
            throw new \Exception('Unable to cancel subscription');
        }

        return $response->json()['data'] ?? [];
    }
}
