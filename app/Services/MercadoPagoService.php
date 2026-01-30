<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class MercadoPagoService
{
    protected string $apiUrl = 'https://api.mercadopago.com';

    public function __construct(
        protected string $accessToken = ''
    ) {
        $this->accessToken = config('services.mercadopago.access_token');

        if (! $this->accessToken) {
            throw new \Exception('Token de acceso de MercadoPago no configurado. Configura MERCADOPAGO_ACCESS_TOKEN en .env');
        }
    }

    public function createSubscription(array $data): array
    {
        $response = Http::withToken($this->accessToken)
            ->timeout(10)
            ->post("{$this->apiUrl}/preapproval", $data);

        if ($response->failed()) {
            throw new \Exception($response->body());
        }

        return $response->json();
    }

    public function getSubscription(string $id): array
    {
        return Http::withToken($this->accessToken)
            ->timeout(10)
            ->get("{$this->apiUrl}/preapproval/{$id}")
            ->throw()
            ->json();
    }

    public function cancelSubscription(string $id): array
    {
        return $this->updateSubscription($id, [
            'status' => 'cancelled',
        ]);
    }

    public function updateSubscription(string $id, array $data): array
    {
        return Http::withToken($this->accessToken)
            ->timeout(10)
            ->put("{$this->apiUrl}/preapproval/{$id}", $data)
            ->throw()
            ->json();
    }
}
