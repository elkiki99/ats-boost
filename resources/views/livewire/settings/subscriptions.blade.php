<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Subscriptions')" :subheading="__('Manage your account subscription')">
        @if ($subscription)
            <flux:card class="p-6 space-y-6">
                <div class ="flex items-center gap-2">
                    <flux:heading>
                        @php
                            $planName = match ($subscription->lemon_variant_id) {
                                '1227617' => 'Monthly',
                                '1227622' => 'Weekly',
                                '1227623' => 'Yearly',
                                default => '',
                            };
                        @endphp
                        ATS Boost {{ $planName }} Plan
                    </flux:heading>

                    <flux:badge size="sm" color="{{ $subscription->active ? 'green' : 'red' }}">
                        {{ $subscription->active ? 'Active' : 'Inactive' }}
                    </flux:badge>
                </div>

                <flux:button href="{{ $subscription->customer_portal_url }}" variant="filled" class="w-full">
                    Manage Subscription
                </flux:button>
            </flux:card>
        @else
            <flux:callout icon="shield-check" color="blue" inline>
                <flux:callout.heading>You don't have an active subscription yet</flux:callout.heading>
                <flux:callout.text>Get access to all of our premium features and benefits.</flux:callout.text>
                <x-slot name="actions" class="@md:h-full m-0!">
                    <flux:button href="{{ route('pricing') }}" wire:navigate>Upgrade to Pro -></flux:button>
                </x-slot>
            </flux:callout>
        @endif
    </x-settings.layout>
</section>
