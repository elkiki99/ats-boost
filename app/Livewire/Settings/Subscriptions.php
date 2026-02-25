<?php

namespace App\Livewire\Settings;

use App\Actions\MercadoPago\HandleSubscriptionPlanChange;
use App\Actions\MercadoPago\SyncSubscription;
use App\Services\MercadoPagoService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Livewire\Component;
use App\Support\Money;
use Flux\Flux;

class Subscriptions extends Component
{
    public $subscription;
    public $newPlan;
    public array $prices = [];

    public function mount(Request $request, MercadoPagoService $mp)
    {
        // Sync ONLY when coming back from MercadoPago
        if ($request->filled('preapproval_id')) {

            app(SyncSubscription::class)->handle([
                'id' => $request->preapproval_id,
                'source' => 'back_url',
            ]);

            $oldSubscriptionId = session('plan_change_old_subscription_id');

            if ($oldSubscriptionId) {

                $success = app(HandleSubscriptionPlanChange::class)->handle(
                    Auth::user(),
                    $request->preapproval_id,
                    $oldSubscriptionId
                );

                if ($success) {
                    Flux::toast(
                        heading: 'Plan actualizado',
                        text: 'Tu suscripción ha sido actualizada exitosamente.',
                        variant: 'success',
                    );
                }

                session()->forget('plan_change_old_subscription_id');
                session()->forget('plan_change_new_plan_id');
            }
        }

        $this->loadSubscription();

        foreach (config('services.mercadopago.plans') as $key => $planId) {
            $price = $mp->getPlanPrice($planId);

            $this->prices[$key] = [
                ...$price,
                'formatted' => Money::format($price['amount'], $price['currency']),
            ];
        }
    }

    protected function loadSubscription()
    {
        $this->subscription = Auth::user()
            ->subscribers()
            ->orderByDesc('ends_at')
            ->first();

        $this->newPlan = $this->subscription?->mp_plan_id;
    }

    public function changePlan()
    {
        if (!$this->subscription) {
            return;
        }

        if ($this->newPlan === $this->subscription->mp_plan_id) {
            return;
        }

        session([
            'plan_change_old_subscription_id' => $this->subscription->mp_subscription_id,
            'plan_change_new_plan_id' => $this->newPlan,
        ]);

        $this->modal('update-suscription')->close();

        return redirect()->away(
            'https://www.mercadopago.com.uy/subscriptions/checkout?preapproval_plan_id=' . $this->newPlan
        );
    }

    public function cancelSubscription()
    {
        if (!$this->subscription) {
            return;
        }

        if ($this->subscription->status === 'cancelled') {
            return;
        }

        app(MercadoPagoService::class)
            ->cancelSubscription($this->subscription->mp_subscription_id);

        app(SyncSubscription::class)->handle([
            'id' => $this->subscription->mp_subscription_id,
            'source' => 'cancel',
        ]);

        $this->loadSubscription();

        Flux::modals()->close();

        Flux::toast(
            heading: 'Suscripción cancelada',
            text: 'Tu suscripción terminará al final del período de facturación.',
            variant: 'success'
        );
    }

    public function render()
    {
        return view('livewire.settings.subscriptions')
            ->title(__('Suscripciones • ATS Boost'));
    }
}
