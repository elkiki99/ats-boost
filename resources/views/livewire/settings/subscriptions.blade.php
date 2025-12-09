<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Subscriptions')" :subheading="__('Manage your account subscriptions')">
        @if (count($subscriptions) === 0)
            <p>No active subscriptions found.</p>
        @else
            <table class="w-full table-auto border-collapse border border-gray-200">
                <thead>
                    <tr>
                        <th class="border px-4 py-2">Order</th>
                        <th class="border px-4 py-2">Date</th>
                        <th class="border px-4 py-2">Status</th>
                        <th class="border px-4 py-2">Product</th>
                        <th class="border px-4 py-2">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($subscriptions as $sub)
                        <tr>
                            <td class="border px-4 py-2">{{ $sub['id'] }}</td>
                            <td class="border px-4 py-2">
                                {{ \Carbon\Carbon::parse($sub['attributes']['created_at'])->format('d M, Y') }}</td>
                            <td class="border px-4 py-2">{{ ucfirst($sub['attributes']['status']) }}</td>
                            <td class="border px-4 py-2">ATS Boost {{ $sub['attributes']['variant_name'] ?? 'N/A' }}
                            </td>
                            <td class="border px-4 py-2">${{ $sub['attributes']['price'] ?? '0.00' }}</td>
                            <td class="border px-4 py-2">
                                <a href="{{ $sub['attributes']['urls']['customer_portal'] ?? '#' }}" target="_blank"
                                    class="text-blue-600 hover:underline">
                                    Ver suscripción / recibo
                                </a>
                            </td>

                            {{-- <button wire:click="cancelSubscription('{{ $sub['id'] }}')"
                                class="text-red-600 hover:underline">
                                Cancelar
                            </button> --}}
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </x-settings.layout>
</section>
