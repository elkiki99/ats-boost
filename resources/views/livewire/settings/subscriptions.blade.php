<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Subscriptions')" :subheading="__('Manage your account subscription')">
        @if ($subscription)
            <flux:card class="p-6 space-y-6">
                <flux:heading>
                    ATS Boost {{ $subscription->variant_name }}
                </flux:heading>

                <flux:subheading>
                    Status: {{ $subscription->active ? 'Active' : 'Inactive' }}
                </flux:subheading>

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
