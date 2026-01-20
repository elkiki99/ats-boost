<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Subscriptions extends Component
{
    public $subscription;
    public $variant;

    public function mount()
    {
        $this->subscription = Auth::user()->subscriber;
        $this->variant = $this->subscription->lemon_variant_id;
    }
}
