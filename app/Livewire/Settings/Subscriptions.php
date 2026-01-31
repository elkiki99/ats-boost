<?php

namespace App\Livewire\Settings;

use App\Actions\MercadoPago\HandleSubscriptionPlanChange;
use App\Actions\MercadoPago\SyncSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Flux\Flux;

class Subscriptions extends Component
{
    public $subscription;

    public $newPlan;

    public function mount(Request $request)
    {
        if (session()->get('subscription_required')) {
            Flux::toast(
                heading: 'Suscripción requerida',
                text: 'Necesitas una suscripción activa para acceder a esta función.',
                variant: 'warning'
            );

            session()->forget('subscription_required');
        }

        // Si vuelve de MP, pedimos sync PERO sin asumir nada
        if ($request->filled('preapproval_id')) {
            app(SyncSubscription::class)->handle([
                'id' => $request->preapproval_id,
                'source' => 'back_url',
            ]);

            // Check if this is a plan change completion
            $oldSubscriptionId = session('plan_change_old_subscription_id');

            if ($oldSubscriptionId) {
                // New subscription is now confirmed, safe to cancel the old one
                $success = app(HandleSubscriptionPlanChange::class)->handle(
                    Auth::user(),
                    $request->preapproval_id,
                    $oldSubscriptionId
                );

                if ($success) {
                    Flux::toast(
                        heading: 'Plan actualizado',
                        text: 'Tu suscripción ha sido actualizada exitosamente.',
                        variant: 'success'
                    );
                } else {
                    Log::warning('Plan change finalization may have failed', [
                        'user_id' => Auth::id(),
                        'new_subscription_id' => $request->preapproval_id,
                    ]);
                }

                // Clean up session
                session()->forget('plan_change_old_subscription_id');
                session()->forget('plan_change_new_plan_id');
            }
        }

        $this->loadSubscription();

        // Always sync latest status from MercadoPago API
        if ($this->subscription) {
            app(SyncSubscription::class)->handle([
                'id' => $this->subscription->mp_subscription_id,
                'source' => 'page_load',
            ]);
            $this->loadSubscription();
        }
    }

    protected function loadSubscription()
    {
        // Get the most recent active/paused subscription, excluding cancelled ones
        $this->subscription = Auth::user()
            ->subscribers()
            ->whereIn('status', ['authorized', 'active', 'paused'])
            ->latest()
            ->first();

        $this->newPlan = $this->subscription?->mp_plan_id;
    }

    public function changePlan()
    {
        if (! $this->subscription || $this->newPlan === $this->subscription->mp_plan_id) {
            return;
        }

        try {
            // Store the plan change intent in session
            // The old subscription will only be cancelled AFTER the new one is confirmed
            session([
                'plan_change_old_subscription_id' => $this->subscription->mp_subscription_id,
                'plan_change_new_plan_id' => $this->newPlan,
            ]);

            $this->modal('update-subscription')->close();

            // Redirect to Mercado Pago checkout with the new plan
            // User will complete payment, then return with new preapproval_id
            // At that point, mount() will finalize by cancelling the old subscription
            return redirect()->away(
                'https://www.mercadopago.com.uy/subscriptions/checkout?preapproval_plan_id=' . $this->newPlan
            );
        } catch (\Throwable $e) {
            Log::error('Subscription changePlan failed', [
                'error' => $e->getMessage(),
                'subscription_id' => $this->subscription?->mp_subscription_id,
            ]);
            Flux::toast(
                heading: 'Error al actualizar',
                text: 'Hubo un error al actualizar tu suscripción.',
                variant: 'danger'
            );
        }
    }

    public function cancelSubscription()
    {
        if (! $this->subscription) {
            return;
        }

        try {
            app(\App\Services\MercadoPagoService::class)
                ->cancelSubscription($this->subscription->mp_subscription_id);

            // Sync the cancelled subscription from MercadoPago API
            app(SyncSubscription::class)->handle([
                'id' => $this->subscription->mp_subscription_id,
                'source' => 'cancel',
            ]);

            $this->loadSubscription();

            $this->modal('cancel-subscription')->close();

            Flux::toast(
                heading: 'Suscripción cancelada',
                text: 'Tu suscripción terminará al final del período de facturación.',
                variant: 'success'
            );
        } catch (\Throwable $e) {
            // Check if already cancelled
            if (str_contains($e->getMessage(), 'already cancel') || str_contains($e->getMessage(), 'You can not modify a cancelled')) {
                // Just reload - it's already cancelled
                app(SyncSubscription::class)->handle([
                    'id' => $this->subscription->mp_subscription_id,
                    'source' => 'cancel_check',
                ]);
                $this->loadSubscription();

                Flux::toast(
                    heading: 'Ya cancelada',
                    text: 'Tu suscripción ya está cancelada.',
                    variant: 'info'
                );

                return;
            }

            Log::error('Subscription cancelSubscription failed', [
                'error' => $e->getMessage(),
                'subscription_id' => $this->subscription?->mp_subscription_id,
            ]);
            Flux::toast(
                heading: 'Error al cancelar',
                text: 'Hubo un error al cancelar tu suscripción.',
                variant: 'danger'
            );
        }
    }

    protected function refreshSubscription()
    {
        $this->subscription = Auth::user()->fresh()->subscriber;
        $this->newPlan = $this->subscription?->mp_plan_id;
    }

    public function render()
    {
        return view('livewire.settings.subscriptions')->title(__('Suscripciones • ATS Boost'));
    }
}
