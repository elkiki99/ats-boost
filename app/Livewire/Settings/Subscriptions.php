<?php

namespace App\Livewire\Settings;

use App\Actions\MercadoPago\SyncSubscription;
use Flux\Flux;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Subscriptions extends Component
{
    public $subscription;

    public $newPlan;

    public function mount(Request $request)
    {
        // Si vuelve de MP, pedimos sync PERO sin asumir nada
        if ($request->filled('preapproval_id')) {
            app(SyncSubscription::class)->handle([
                'id' => $request->preapproval_id,
                'source' => 'back_url',
            ]);
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
            app(\App\Services\MercadoPagoService::class)
                ->updateSubscription(
                    $this->subscription->mp_subscription_id,
                    ['preapproval_plan_id' => $this->newPlan]
                );

            // Sync the updated subscription from MercadoPago API
            app(SyncSubscription::class)->handle([
                'id' => $this->subscription->mp_subscription_id,
                'source' => 'plan_change',
            ]);

            $this->loadSubscription();

            $this->modal('update-subscription')->close();

            Flux::toast(
                heading: 'Subscription updated',
                text: 'Your subscription plan has been changed successfully.',
                variant: 'success'
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

    public function resumeSubscription()
    {
        if (! $this->subscription) {
            return;
        }

        try {
            app(\App\Services\MercadoPagoService::class)
                ->resumeSubscription($this->subscription->mp_subscription_id);

            // Sync the resumed subscription from MercadoPago API
            app(SyncSubscription::class)->handle([
                'id' => $this->subscription->mp_subscription_id,
                'source' => 'resume',
            ]);

            $this->loadSubscription();

            $this->modal('resume-subscription')->close();

            Flux::toast(
                heading: 'Suscripción reanudada',
                text: 'Tu suscripción ha sido reanudada exitosamente.',
                variant: 'success'
            );
        } catch (\Throwable $e) {
            // Check if already active
            if (str_contains($e->getMessage(), 'already authorized') || str_contains($e->getMessage(), 'You can not modify')) {
                app(SyncSubscription::class)->handle([
                    'id' => $this->subscription->mp_subscription_id,
                    'source' => 'resume_check',
                ]);
                $this->loadSubscription();

                Flux::toast(
                    heading: 'Ya activa',
                    text: 'Tu suscripción ya está activa.',
                    variant: 'info'
                );

                return;
            }

            Log::error('Subscription resumeSubscription failed', [
                'error' => $e->getMessage(),
                'subscription_id' => $this->subscription?->mp_subscription_id,
            ]);
            Flux::toast(
                heading: 'Error al reanudar',
                text: 'Hubo un error al reanudar tu suscripción.',
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
