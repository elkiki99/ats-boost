@props([
    'title',
    'description' => null,
])

<div class="mb-6 w-full">
    <flux:heading size="xl" level="1">{{ $title }}</flux:heading>

    @if ($description)
        <flux:subheading size="lg" class="mb-6">{{ $description }}</flux:subheading>
    @endif

    <flux:separator variant="subtle" />
</div>
