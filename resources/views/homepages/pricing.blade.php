<x-layouts.main title="Precios • ATS Boost">
    <section class="space-y-6">
        <div class="!text-center">
            <flux:badge color="blue" icon="cursor-arrow-ripple" size="sm" variant="pill">
                Alto impacto, bajo costo
            </flux:badge>
        </div>

        <!-- Desktop -->
        <flux:heading level="1"
            class="!text-5xl md:!text-6xl !mb-6 font-black max-w-4xl mx-auto lg:max-w-full text-center hidden md:block">
            Listo para un currículum ganador<br class="hidden lg:block">
            que te consiga trabajo<span
                class="text-5xl font-black text-transparent md:text-6xl dark:from-blue-500 dark:via-blue-300 dark:to-blue-600 bg-gradient-to-r from-blue-600 via-blue-400 to-blue-700 bg-clip-text">
                rápidamente?</span>
        </flux:heading>

        <!-- Mobile -->
        <flux:heading level="1" class="md:hidden !text-5xl text-center">Precios</flux:heading>

        <div class="py-6 space-y-6">
            <livewire:pricing-plans />

            <flux:separator variant="subtle" />

            <div class="flex items-center justify-center gap-2 text-center">
                <flux:subheading>¿Aún no estás seguro? Prueba nuestra <flux:link class="!text-sm"
                        wire:navigate href="{{ route('home') }}">demo</flux:link>.</flux:subheading>
            </div>
        </div>
    </section>
</x-layouts.main>
