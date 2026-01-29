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
                            <div class="mt-2 text-xs space-y-1">
                                <div>ID de suscripción: {{ $subscription->mp_subscription_id ?? '—' }}</div>
                                <div>Pagador: {{ $subscription->payer_email ?? '—' }}</div>
                            </div>
                        </flux:callout.text>
                    </flux:callout>
                @elseif ($subscription->status === 'paused')
                    <flux:callout icon="pause-circle" color="amber">
                        <flux:callout.heading>
                            Suscripción pausada
                        </flux:callout.heading>
                        <flux:callout.text>
                            Tu suscripción está actualmente pausada.
                            <div class="mt-2 text-xs space-y-1">
                                <div>ID de suscripción: {{ $subscription->mp_subscription_id ?? '—' }}</div>
                                <div>Pagador: {{ $subscription->payer_email ?? '—' }}</div>
                            </div>
                        </flux:callout.text>
                    </flux:callout>
                @elseif ($subscription->status === 'authorized' || $subscription->status === 'active')
                    <flux:callout icon="check-circle" color="green">
                        <flux:callout.heading>
                            Suscripción activa
                        </flux:callout.heading>
                        <flux:callout.text>
                            Tu suscripción está activa y los beneficios están habilitados.
                            <div class="mt-2 text-xs space-y-1">
                                <div>ID de suscripción: {{ $subscription->mp_subscription_id ?? '—' }}</div>
                                <div>Pagador: {{ $subscription->payer_email ?? '—' }}</div>
                                <div>Próximo pago:
                                    {{ $subscription->renews_at ? $subscription->renews_at->format('M d, Y') : '—' }}
                                </div>
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
                            <div class="mt-2 text-xs space-y-1">
                                <div>ID de suscripción: {{ $subscription->mp_subscription_id ?? '—' }}</div>
                                <div>Pagador: {{ $subscription->payer_email ?? '—' }}</div>
                            </div>
                        </flux:callout.text>
                    </flux:callout>
                @endif
            </div>

            <div class="space-y-6">
                @if ($subscription->status !== 'cancelled')
                    {{-- Change plan (hidden for cancelled subscriptions) --}}
                    <flux:select variant="listbox" wire:model.live="newPlan" label="Actualizar plan" required>
                        {{-- Monthly --}}
                        <flux:select.option value="87a920276b3844c0a6b4a582589e2fca"
                            :disabled="$subscription->mp_plan_id === '87a920276b3844c0a6b4a582589e2fca'">
                            <div class="flex items-center justify-between gap-2 w-full">
                                <span>Plan mensual – $4.99 / mes</span>

                                @if ($subscription->mp_plan_id === '87a920276b3844c0a6b4a582589e2fca')
                                    <flux:badge size="sm" color="green">Actual</flux:badge>
                                @endif
                            </div>
                        </flux:select.option>

                        {{-- Weekly --}}
                        <flux:select.option value="58902fcda81d4a05ada50f7935dfbecf"
                            :disabled="$subscription->mp_plan_id === '58902fcda81d4a05ada50f7935dfbecf'">
                            <div class="flex items-center justify-between gap-2 w-full">
                                <span>Plan semanal – $1.99 / semana</span>

                                @if ($subscription->mp_plan_id === '58902fcda81d4a05ada50f7935dfbecf')
                                    <flux:badge size="sm" color="green">Actual</flux:badge>
                                @endif
                            </div>
                        </flux:select.option>

                        {{-- Yearly --}}
                        <flux:select.option value="81c71d0de5834d1d9837ff601821e344"
                            :disabled="$subscription->mp_plan_id === '81c71d0de5834d1d9837ff601821e344'">
                            <div class="flex items-center justify-between gap-2 w-full">
                                <span>Plan anual – $39.99 / año</span>

                                @if ($subscription->mp_plan_id === '81c71d0de5834d1d9837ff601821e344')
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
                @elseif ($subscription->status === 'paused')
                    {{-- Paused (can be resumed) --}}
                    <div class="space-y-4">
                        <flux:heading>Reanudar plan</flux:heading>
                        <flux:subheading>
                            Tu suscripción está actualmente pausada. Reanúdala para continuar recibiendo funciones
                            premium.
                        </flux:subheading>

                        <flux:modal.trigger name="resume-subscription">
                            <flux:button variant="primary" color="green">
                                Reanudar suscripción
                            </flux:button>
                        </flux:modal.trigger>
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

            <flux:modal name="resume-subscription" class="md:w-96">
                <div class="space-y-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <flux:heading size="lg">¿Reanudar suscripción?</flux:heading>
                            <flux:text class="mt-2"> Tu suscripción será reanudada.<br> Recuperarás acceso a todas las
                                funciones premium. </flux:text>
                        </div>
                    </div>
                    <div class="flex justify-end gap-4">
                        <flux:button x-on:click="$flux.modal('resume-subscription').close()" variant="ghost"> Deshacer
                        </flux:button>
                        <flux:button variant="primary" color="green" wire:click="resumeSubscription"> Reanudar
                            suscripción
                        </flux:button>
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
