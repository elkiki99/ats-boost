<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Subscriptions')" :subheading="__('Manage your account subscription')">
        @if ($subscription)
            <flux:card class="p-6 space-y-6">
                <flux:heading>
                    ATS Boost {{ $subscription['attributes']['variant_name'] }}
                </flux:heading>

                @php
                    $status = match ($subscription['attributes']['status']) {
                        'on_trial' => 'Trial',
                        'active' => 'Active',
                        default => ucfirst($subscription['attributes']['status']),
                    };
                @endphp

                <flux:subheading>Status: {{ $status }}</flux:subheading>
                
                <flux:button href="{{ $subscription['attributes']['urls']['customer_portal'] }}" variant="filled"
                    class="w-full">
                    Manage Subscription
                </flux:button>
            </flux:card>
        @else
            <flux:text class="text-gray-500">No active subscription found.</flux:text>
        @endif
    </x-settings.layout>
</section>
