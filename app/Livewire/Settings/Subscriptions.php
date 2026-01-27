<?php

namespace App\Livewire\Settings;

use App\Actions\MercadoPago\SyncSubscription;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Livewire\Component;
use Flux\Flux;

class Subscriptions extends Component
{
    public $subscription;
    public $newPlan;

    public function mount(Request $request)
    {
        // 1️⃣ Si viene de MercadoPago, sincronizamos
        if ($request->filled('preapproval_id')) {
            app(SyncSubscription::class)->handle([
                'id' => $request->preapproval_id,
                'source' => 'back_url',
            ]);
        }

        // 2️⃣ Cargamos el estado actual SIEMPRE después
        $this->subscription = Auth::user()->fresh()->subscriber;
        $this->newPlan = $this->subscription->mp_plan_id ?? null;
    }

    // public function changePlan()
    // {
    //     if ($this->newPlan === $this->subscription->lemon_variant_id) {
    //         return;
    //     }

    //     app(LemonSqueezyService::class)
    //         ->changePlan(
    //             $this->subscription->lemon_subscription_id,
    //             $this->newPlan
    //         );

    //     // $this->refreshSubscription();
    //     $this->modal('update-subscription')->close();

    //     Flux::toast(
    //         heading: 'Subscription updated',
    //         text: 'Your subscription plan has been changed successfully.',
    //         variant: 'success'
    //     );
    // }

    // public function cancelSubscription()
    // {
    //     app(LemonSqueezyService::class)
    //         ->cancelSubscription(
    //             $this->subscription->lemon_subscription_id
    //         );

    //     $this->modal('cancel-subscription')->close();

    //     // $this->refreshSubscription();

    //     Flux::toast(
    //         heading: 'Subscription cancelled',
    //         text: 'Your subscription will end at the end of the billing period.',
    //         variant: 'success'
    //     );
    // }

    // public function resumeSubscription()
    // {
    //     app(LemonSqueezyService::class)
    //         ->resumeSubscription(
    //             $this->subscription->lemon_subscription_id
    //         );

    //     $this->modal('resume-subscription')->close();

    //     // $this->refreshSubscription();

    //     Flux::toast(
    //         heading: 'Subscription resumed',
    //         text: 'Your subscription has been resumed successfully.',
    //         variant: 'success'
    //     );
    // }

    // protected function refreshSubscription()
    // {
    //     $this->subscription = Auth::user()->fresh()->subscriber;
    //     $this->newPlan = $this->subscription->lemon_variant_id;
    // }
}
