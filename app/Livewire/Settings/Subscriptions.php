<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use App\Services\LemonSqueezyService;
use Illuminate\Support\Facades\Auth;

class Subscriptions extends Component
{
    public array $subscriptions = [];

    public function mount(LemonSqueezyService $lemon)
    {
        $user = Auth::user();
        $this->subscriptions = $lemon->getSubscriptionsByEmail($user->email);
    }

    // public function updateSubscription(LemonSqueezyService $lemon) {}

    public function cancelSubscription(string $subscriptionId, LemonSqueezyService $lemon)
    {
        $user = Auth::user();
        $lemon->cancelSubscription($subscriptionId);

        $subscriptions = $lemon->getSubscriptionsByEmail($user->email);

        // Flux::modal();
    }
}
