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

        // Si algo falla, retornamos error
        if ($response->failed() || empty($data['data']['attributes']['url'])) {
            return null;
        }

        // Retornamos la URL del checkout
        return $data['data']['attributes']['url'];
    }

    public function changePlan(
        string $subscriptionId,
        string $variantId,
        bool $invoiceImmediately = false,
        bool $disableProrations = false
    ): void {
        $attributes = [
            'variant_id' => (int) $variantId,
        ];

        if ($invoiceImmediately) {
            $attributes['invoice_immediately'] = true;
        }

        if ($disableProrations) {
            $attributes['disable_prorations'] = true;
        }

        Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Accept' => 'application/vnd.api+json',
            'Content-Type' => 'application/vnd.api+json',
        ])->patch("{$this->apiUrl}/subscriptions/{$subscriptionId}", [
            'data' => [
                'type' => 'subscriptions',
                'id' => $subscriptionId,
                'attributes' => $attributes,
            ],
        ]);
    }

    public function cancelSubscription(string $subscriptionId): array
    {
        $response = Http::withHeaders([
            'Accept' => 'application/vnd.api+json',
            'Content-Type' => 'application/vnd.api+json',
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->delete("{$this->apiUrl}/subscriptions/{$subscriptionId}");

        if ($response->failed()) {
            throw new \Exception('Unable to cancel subscription');
        }

        return $response->json();
    }

    public function resumeSubscription(string $subscriptionId): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Accept' => 'application/vnd.api+json',
            'Content-Type' => 'application/vnd.api+json',
        ])->patch("{$this->apiUrl}/subscriptions/{$subscriptionId}", [
            'data' => [
                'type' => 'subscriptions',
                'id' => $subscriptionId,
                'attributes' => [
                    'cancelled' => false,
                ],
            ],
        ]);

        if ($response->failed()) {
            throw new \Exception('Unable to resume subscription');
        }

        return $response->json();
    }
}
