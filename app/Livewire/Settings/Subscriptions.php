<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use App\Services\LemonSqueezyService;
use Illuminate\Support\Facades\Auth;

class Subscriptions extends Component
{
    public $subscription;

    public function mount(LemonSqueezyService $lemon)
    {
        $user = Auth::user();
        $this->subscription = $lemon->getActualSubscription($user->email);
    }

    // public function updateSubscription(LemonSqueezyService $lemon) {}

    // public function cancelSubscription(string $subscriptionId, LemonSqueezyService $lemon)
    // {
    //     $user = Auth::user();
    //     $lemon->cancelSubscription($subscriptionId);

    //     $this->subscription = $lemon->getActualSubscription($user->email);

    //     // Flux::modal();
    // }
}
