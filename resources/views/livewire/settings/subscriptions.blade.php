<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Subscriptions')" :subheading="__('Manage your account subscription')">
        @if ($subscription)
            {{-- 🔔 Informational callouts --}}
            <div class="space-y-4 mb-6">
                {{-- Cancelled but still active --}}
                @if (!$subscription->active && $subscription->ends_at && now()->lt($subscription->ends_at))
                    <flux:callout icon="shield-exclamation" color="amber">
                        <flux:callout.heading>
                            Subscription cancelled
                        </flux:callout.heading>
                        <flux:callout.text>
                            Your access will remain active until
                            {{ $subscription->ends_at->format('M d, Y') }}.
                        </flux:callout.text>
                    </flux:callout>
                @endif

                {{-- Expired --}}
                @if (!$subscription->active && $subscription->ends_at && now()->gte($subscription->ends_at))
                    <flux:callout icon="shield-exclamation" color="red">
                        <flux:callout.heading>
                            Subscription expired
                        </flux:callout.heading>
                        <flux:callout.text>
                            Your subscription has ended. You can renew or choose a new plan at any time.
                        </flux:callout.text>
                    </flux:callout>
                @endif

            </div>

            {{-- ⚙️ Subscription management (always visible) --}}
            <div class="space-y-6">

                {{-- Change plan --}}
                <flux:select variant="listbox" wire:model.live="newPlan" label="Update plan" required>
                    {{-- Monthly --}}
                    <flux:select.option value="1227617" :disabled="$subscription->lemon_variant_id === '1227617'">
                        <div class="flex items-center justify-between gap-2 w-full">
                            <span>Monthly Plan – $4.99 / month</span>

                            @if ($subscription->lemon_variant_id === '1227617')
                                <flux:badge size="sm" color="green">Current</flux:badge>
                            @endif
                        </div>
                    </flux:select.option>

                    {{-- Weekly --}}
                    <flux:select.option value="1227622" :disabled="$subscription->lemon_variant_id === '1227622'">
                        <div class="flex items-center justify-between gap-2 w-full">
                            <span>Weekly Plan – $1.99 / week</span>

                            @if ($subscription->lemon_variant_id === '1227622')
                                <flux:badge size="sm" color="green">Current</flux:badge>
                            @endif
                        </div>
                    </flux:select.option>

                    {{-- Yearly --}}
                    <flux:select.option value="1227623" :disabled="$subscription->lemon_variant_id === '1227623'">
                        <div class="flex items-center justify-between gap-2 w-full">
                            <span>Yearly Plan – $39.99 / year</span>

                            @if ($subscription->lemon_variant_id === '1227623')
                                <flux:badge size="sm" color="green">Current</flux:badge>
                            @endif
                        </div>
                    </flux:select.option>
                </flux:select>

                <flux:modal.trigger name="update-subscription">
                    <flux:button variant="primary" :disabled="$newPlan === $subscription->lemon_variant_id">
                        Update subscription
                    </flux:button>
                </flux:modal.trigger>

                <div class="mt-6"></div>

                <flux:separator />

                {{--  Resume --}}
                @if (!$subscription->active && $subscription->ends_at && now()->lt($subscription->ends_at))
                    <div class="space-y-4">
                        <flux:heading>Resume plan</flux:heading>
                        <flux:subheading>
                            Your subscription is currently canceled but still active until the end of the billing
                            period.
                        </flux:subheading>

                        <flux:modal.trigger name="resume-subscription">
                            <flux:button variant="primary" color="green">
                                Resume subscription
                            </flux:button>
                        </flux:modal.trigger>
                    </div>
                @elseif (!$subscription->active && $subscription->ends_at && now()->gte($subscription->ends_at))
                    <div class="space-y-4">
                        <flux:heading>Renew plan</flux:heading>
                        <flux:subheading>
                            Your subscription has expired. Renew your subscription to regain access to premium
                            features.
                        </flux:subheading>

                        <flux:modal.trigger name="renew-subscription">
                            <flux:button variant="primary" wire:click="renewSubscription">
                                Renew subscription
                            </flux:button>
                        </flux:modal.trigger>
                    </div>
                @else
                    {{-- Cancel --}}
                    <div class="space-y-4">
                        <flux:heading>Cancel plan</flux:heading>
                        <flux:subheading>
                            Canceling your subscription will keep your access until the end
                            of the current billing period.
                        </flux:subheading>
                        <flux:modal.trigger name="cancel-subscription">
                            <flux:button variant="danger">
                                Cancel subscription
                            </flux:button>
                        </flux:modal.trigger>
                    </div>
                @endif
            </div>

            {{-- Modals --}}
            <flux:modal name="update-subscription" class="md:w-96">
                <div class="space-y-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <flux:heading size="lg">Update subscription?</flux:heading>
                            <flux:text class="mt-2"> Your subscription will be updated.<br> No extra charges unless
                                you upgrade to a higher plan. </flux:text>
                        </div>
                    </div>
                    <div class="flex justify-end gap-4">
                        <flux:button x-on:click="$flux.modal('update-subscription').close()" variant="ghost"> Undo
                        </flux:button>
                        <flux:button variant="primary" wire:click="changePlan"> Update subscription </flux:button>
                    </div>
                </div>
            </flux:modal>

            <flux:modal name="resume-subscription" class="md:w-96">
                <div class="space-y-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <flux:heading size="lg">Resume subscription?</flux:heading>
                            <flux:text class="mt-2"> Your subscription will be resumed.<br> You will regain access to
                                all premium features. </flux:text>
                        </div>
                    </div>
                    <div class="flex justify-end gap-4">
                        <flux:button x-on:click="$flux.modal('resume-subscription').close()" variant="ghost"> Undo
                        </flux:button>
                        <flux:button variant="primary" color="green" wire:click="resumeSubscription"> Resume
                            subscription
                        </flux:button>
                    </div>
                </div>
            </flux:modal>

            <flux:modal name="cancel-subscription" class="md:w-96">
                <div class="space-y-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <flux:heading size="lg">Cancel subscription?</flux:heading>
                            <flux:text class="mt-2"> Your subscription will be canceled.<br> You will lose access to
                                all premium features at the end of your billing period. </flux:text>
                        </div>
                    </div>
                    <div class="flex justify-end gap-4">
                        <flux:button x-on:click="$flux.modal('cancel-subscription').close()" variant="ghost"> Undo
                        </flux:button>
                        <flux:button variant="danger" wire:click="cancelSubscription"> Cancel subscription
                        </flux:button>
                    </div>
                </div>
            </flux:modal>
        @else
            {{-- No subscription --}}
            <flux:callout icon="shield-check" color="blue" inline>
                <flux:callout.heading>
                    You don't have an active subscription yet
                </flux:callout.heading>
                <flux:callout.text>
                    Get access to all of our premium features and benefits.
                </flux:callout.text>

                <x-slot name="actions">
                    <flux:button icon-trailing="arrow-right" href="{{ route('pricing') }}" wire:navigate>
                        Upgrade to Pro
                    </flux:button>
                </x-slot>
            </flux:callout>
        @endif
    </x-settings.layout>
</section>
