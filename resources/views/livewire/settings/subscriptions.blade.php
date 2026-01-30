<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Suscripciones')" :subheading="__('Gestiona tu suscripción de cuenta')">
        @if ($subscription)
            {{-- Status-based callouts with subscription info --}}
            <div class="space-y-4 mb-6">
                @if ($subscription->status === 'cancelled')
                    <flux:callout icon="shield-exclamation" color="red">
                        <flux:callout.heading>
                            Suscripción cancelada
                        </flux:callout.heading>
                        <flux:callout.text>
                            Tu suscripción ha sido cancelada permanentemente.
                        </flux:callout.text>
                    </flux:callout>
                @elseif ($subscription->status === 'authorized' || $subscription->status === 'active')
                    <flux:callout icon="check-circle" color="green">
                        <flux:callout.heading>
                            Suscripción activa
                        </flux:callout.heading>
                        <flux:callout.text>
                            Tu suscripción está activa y los beneficios están habilitados.
            </div>
            </flux:callout.text>
            </flux:callout>
        @else
            <flux:callout icon="clock" color="amber">
                <flux:callout.heading>
                    Suscripción pendiente
                </flux:callout.heading>
                <flux:callout.text>
                    Tu suscripción está siendo procesada.
                </flux:callout.text>
            </flux:callout>
        @endif
        </div>

        <div class="space-y-6">
            @if ($subscription->status !== 'cancelled')
                {{-- Change plan (hidden for cancelled subscriptions) --}}
                <flux:select variant="listbox" wire:model.live="newPlan" label="Actualizar plan" required>
                    {{-- Monthly --}}
                    <flux:select.option value="{{ config('services.mercadopago.plans.monthly') }}"
                        :disabled="$subscription->mp_plan_id === config('services.mercadopago.plans.monthly')">
                        <div class="flex items-center justify-between gap-2 w-full">
                            <span>Plan mensual – $4.99 / mes</span>

                            @if ($subscription->mp_plan_id === config('services.mercadopago.plans.monthly'))
                                <flux:badge size="sm" color="green">Actual</flux:badge>
                            @endif
                        </div>
                    </flux:select.option>

                    {{-- Weekly --}}
                    <flux:select.option value="{{ config('services.mercadopago.plans.weekly') }}"
                        :disabled="$subscription->mp_plan_id === config('services.mercadopago.plans.weekly')">
                        <div class="flex items-center justify-between gap-2 w-full">
                            <span>Plan semanal – $1.99 / semana</span>

                            @if ($subscription->mp_plan_id === config('services.mercadopago.plans.weekly'))
                                <flux:badge size="sm" color="green">Actual</flux:badge>
                            @endif
                        </div>
                    </flux:select.option>

                    {{-- Yearly --}}
                    <flux:select.option value="{{ config('services.mercadopago.plans.yearly') }}"
                        :disabled="$subscription->mp_plan_id === config('services.mercadopago.plans.yearly')">
                        <div class="flex items-center justify-between gap-2 w-full">
                            <span>Plan anual – $39.99 / año</span>

                            @if ($subscription->mp_plan_id === config('services.mercadopago.plans.yearly'))
                                <flux:badge size="sm" color="green">Actual</flux:badge>
                            @endif
                        </div>
                    </flux:select.option>
                </flux:select>

                <flux:modal.trigger name="update-subscription">
                    <flux:button variant="primary" :disabled="$newPlan === $subscription->mp_plan_id">
                        Actualizar suscripción
                    </flux:button>
                </flux:modal.trigger>

                <div class="mt-6"></div>

                <flux:separator />
            @endif

            {{-- Status-based actions --}}
            @if ($subscription->status === 'cancelled')
                {{-- Cancelled (permanent) --}}
                <div class="space-y-4">
                    <flux:heading>Suscripción cancelada</flux:heading>
                    <flux:subheading>
                        Tu suscripción ha sido cancelada permanentemente. Para recuperar el acceso a funciones
                        premium, necesitarás crear una nueva suscripción.
                    </flux:subheading>

                    <flux:button href="{{ route('pricing') }}" wire:navigate>
                        Crear nueva suscripción
                    </flux:button>
                </div>
            @elseif ($subscription->status === 'authorized' || $subscription->status === 'active')
                {{-- Active (can be cancelled) --}}
                <div class="space-y-4">
                    <flux:heading>Cancelar plan</flux:heading>
                    <flux:subheading>
                        Cancelar tu suscripción mantendrá tu acceso hasta el final del período de facturación
                        actual.
                    </flux:subheading>
                    <flux:modal.trigger name="cancel-subscription">
                        <flux:button variant="danger">
                            Cancelar suscripción
                        </flux:button>
                    </flux:modal.trigger>
                </div>
            @else
                {{-- Pending or other status --}}
                <div class="space-y-4">
                    <flux:heading>Suscripción pendiente</flux:heading>
                    <flux:subheading>
                        Tu suscripción está actualmente en estado pendiente. Vuelve pronto.
                    </flux:subheading>
                </div>
            @endif
        </div>

        {{-- Modals --}}
        <flux:modal name="update-subscription" class="md:w-96">
            <div class="space-y-6">
                <div class="flex items-start justify-between">
                    <div>
                        <flux:heading size="lg">¿Actualizar suscripción?</flux:heading>
                        <flux:text class="mt-2"> Tu suscripción será actualizada.<br> Sin cargos adicionales a
                            menos que actualices a un plan superior. </flux:text>
                    </div>
                </div>
                <div class="flex justify-end gap-4">
                    <flux:button x-on:click="$flux.modal('update-subscription').close()" variant="ghost"> Deshacer
                    </flux:button>
                    <flux:button variant="primary" wire:click="changePlan"> Actualizar suscripción </flux:button>
                </div>
            </div>
        </flux:modal>

        <flux:modal name="cancel-subscription" class="md:w-96">
            <div class="space-y-6">
                <div class="flex items-start justify-between">
                    <div>
                        <flux:heading size="lg">¿Cancelar suscripción?</flux:heading>
                        <flux:text class="mt-2"> Tu suscripción será cancelada.<br> Perderás acceso a todas las
                            funciones premium al final de tu período de facturación. </flux:text>
                    </div>
                </div>
                <div class="flex justify-end gap-4">
                    <flux:button x-on:click="$flux.modal('cancel-subscription').close()" variant="ghost"> Deshacer
                    </flux:button>
                    <flux:button variant="danger" wire:click="cancelSubscription"> Cancelar suscripción
                    </flux:button>
                </div>
            </div>
        </flux:modal>
    @else
        {{-- No subscription --}}
        <flux:callout icon="shield-check" color="blue" inline>
            <flux:callout.heading>
                Aún no tienes una suscripción activa
            </flux:callout.heading>
            <flux:callout.text>
                Obtén acceso a todas nuestras funciones y beneficios premium.
            </flux:callout.text>

            <x-slot name="actions">
                <flux:button icon-trailing="arrow-right" href="{{ route('pricing') }}" wire:navigate>
                    Actualizar a Pro
                </flux:button>
            </x-slot>
        </flux:callout>
        @endif
    </x-settings.layout>
</section>
