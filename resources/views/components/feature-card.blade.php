@props(['icon', 'color' => 'text-blue-600 dark:text-blue-400', 'title'])

<flux:card size="sm"
    class="
        p-6
        relative overflow-hidden
        backdrop-blur-sm
        transition-all duration-300
        hover:-translate-y-[2px]
        hover:shadow-md dark:hover:shadow-none
        hover:shadow-blue-500/5
        rounded-xl
    ">
    {{-- Soft background glow --}}
    <div class="absolute inset-0 pointer-events-none opacity-0 hover:opacity-100 transition duration-500">
        <div class="absolute -inset-10 bg-gradient-to-br from-blue-500/10 via-blue-300/5 to-blue-600/10 blur-2xl">
        </div>
    </div>

    {{-- Icon --}}
    <flux:icon :name="$icon" variant="solid" class="mb-3 {{ $color }}" />

    {{-- Title --}}
    <flux:heading size="lg" level="3">
        {{ $title }}
    </flux:heading>

    {{-- Description --}}
    <flux:subheading size="lg">
        {{ $slot }}
    </flux:subheading>
</flux:card>
