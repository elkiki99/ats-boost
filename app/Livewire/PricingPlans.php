<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\MercadoPagoService;
use App\Support\Money;
class PricingPlans extends Component
{
    public array $prices = [];

    public function mount(MercadoPagoService $mp)
    {
        foreach (config('services.mercadopago.plans') as $key => $planId) {
            $price = $mp->getPlanPrice($planId);

            $this->prices[$key] = [
                ...$price,
                'formatted' => Money::format($price['amount'], $price['currency']),
            ];
        }
    }

    public function render()
    {
        return view('livewire.pricing-plans');
    }
}
