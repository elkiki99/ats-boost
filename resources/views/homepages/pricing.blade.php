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

        <flux:subheading level="2" class="max-w-xl mx-auto text-sm text-center md:hidden">¿Listo para un currículum
            ganador
            que te consiga trabajo?</flux:subheading>

        <div class="py-6 space-y-6">
            <!-- Pricing -->
            <div class="flex flex-col w-full max-w-md gap-6 py-6 mx-auto lg:flex-row lg:max-w-none lg:gap-0">

                <!-- Weekly Plan -->
                <div
                    class="flex flex-col flex-1 w-full gap-2 p-2 border rounded-2xl border-zinc-200 dark:border-zinc-700/75 bg-zinc-100 dark:bg-zinc-900 lg:mt-10 lg:pr-0 lg:border-r-0 lg:rounded-r-none">

                    <flux:card class="flex flex-col h-full p-6  rounded-lg shadow-sm md:p-8 lg:rounded-r-none">
                        <div class="space-y-6">
                            <div class="space-y-2">
                                <flux:subheading class="!text-sm">Plan Semanal</flux:subheading>
                                <flux:heading class="!text-3xl">$1.99 / semana</flux:heading>
                            </div>

                            <!-- Features -->
                            <div class="flex flex-col gap-2">
                                <div class="flex items-center gap-2">
                                    <flux:icon.fire class="text-amber-600 dark:text-amber-400" variant="solid" />
                                    <flux:heading>Currículums personalizados ilimitados</flux:heading>
                                </div>

                                <div class="flex items-center gap-2">
                                    <flux:icon.check-circle class="text-green-600 dark:text-green-400"
                                        variant="solid" />
                                    <flux:heading>Optimización de palabras clave para ATS</flux:heading>
                                </div>

                                <div class="flex items-center gap-2">
                                    <flux:icon.check-circle class="text-green-600 dark:text-green-400"
                                        variant="solid" />
                                    <flux:heading>Mejoras de gramática y claridad</flux:heading>
                                </div>

                                <div class="flex items-center gap-2">
                                    <flux:icon.check-circle class="text-green-600 dark:text-green-400"
                                        variant="solid" />
                                    <flux:heading>Generador de cartas de presentación</flux:heading>
                                </div>

                                <div class="flex items-center gap-2">
                                    <flux:icon.check-circle class="text-green-600 dark:text-green-400"
                                        variant="solid" />
                                    <flux:heading>Puntuación y mejora de ATS</flux:heading>
                                </div>

                                <div class="flex items-center gap-2">
                                    <flux:icon.check-circle class="text-green-600 dark:text-green-400"
                                        variant="solid" />
                                    <flux:heading>Formato profesional</flux:heading>
                                </div>
                            </div>

                            <flux:button class="w-full" as="link"
                                href="{{ route('checkout.start', ['variant' => config('services.mercadopago.plans.weekly')]) }}"
                                variant="filled" icon-trailing="chevron-right">
                                Prueba gratis
                            </flux:button>
                        </div>
                    </flux:card>
                </div>

                <!-- Monthly Plan (Most Popular) -->
                <div
                    class="flex flex-col flex-1 gap-2 p-2 border-2 border-blue-800 rounded-2xl dark:border-blue-200 bg-zinc-100 dark:bg-zinc-900 lg:-mb-4">

                    <flux:card class="flex flex-col h-full p-6  rounded-lg shadow-sm md:p-8 lg:pb-12">
                        <div class="space-y-6">
                            <div class="space-y-2">
                                <flux:badge icon="fire" size="sm" color="green" class="mb-2">Más popular
                                </flux:badge>
                                <flux:subheading class="!text-sm">Plan Mensual</flux:subheading>
                                <flux:heading class="!text-3xl">$4.99 / mes</flux:heading>
                            </div>

                            <!-- Features -->
                            <div class="flex flex-col gap-2">
                                <div class="flex items-center gap-2">
                                    <flux:icon.fire class="text-amber-600 dark:text-amber-400" variant="solid" />
                                    <flux:heading>Currículums personalizados ilimitados</flux:heading>
                                </div>

                                <div class="flex items-center gap-2">
                                    <flux:icon.check-circle class="text-green-600 dark:text-green-400"
                                        variant="solid" />
                                    <flux:heading>Optimización de palabras clave para ATS</flux:heading>
                                </div>

                                <div class="flex items-center gap-2">
                                    <flux:icon.check-circle class="text-green-600 dark:text-green-400"
                                        variant="solid" />
                                    <flux:heading>Mejoras de gramática y claridad</flux:heading>
                                </div>

                                <div class="flex items-center gap-2">
                                    <flux:icon.check-circle class="text-green-600 dark:text-green-400"
                                        variant="solid" />
                                    <flux:heading>Generador de cartas de presentación</flux:heading>
                                </div>

                                <div class="flex items-center gap-2">
                                    <flux:icon.check-circle class="text-green-600 dark:text-green-400"
                                        variant="solid" />
                                    <flux:heading>Puntuación y mejora de ATS</flux:heading>
                                </div>

                                <div class="flex items-center gap-2">
                                    <flux:icon.check-circle class="text-green-600 dark:text-green-400"
                                        variant="solid" />
                                    <flux:heading>Formato profesional</flux:heading>
                                </div>
                            </div>

                            <flux:button class="w-full" as="link"
                                href="{{ route('checkout.start', ['variant' => config('services.mercadopago.plans.monthly')]) }}"
                                variant="primary" icon-trailing="chevron-right">
                                Prueba gratis
                            </flux:button>
                        </div>
                    </flux:card>
                </div>

                <!-- Yearly Plan -->
                <div
                    class="flex flex-col flex-1 gap-2 p-2 border rounded-2xl border-zinc-200 dark:border-zinc-700/75 bg-zinc-100 dark:bg-zinc-900 lg:mt-10 lg:rounded-l-none lg:border-l-0 lg:pl-0">

                    <flux:card class="flex flex-col h-full p-6  rounded-lg shadow-sm md:p-8 lg:rounded-l-none">
                        <div class="space-y-6">

                            <div class="space-y-2">
                                <flux:subheading class="!text-sm">Plan Anual</flux:subheading>
                                <flux:heading class="!text-3xl">$39.99 / año</flux:heading>
                            </div>

                            <!-- Features -->
                            <div class="flex flex-col gap-2">
                                <div class="flex items-center gap-2">
                                    <flux:icon.fire class="text-amber-600 dark:text-amber-400" variant="solid" />
                                    <flux:heading>Currículums personalizados ilimitados</flux:heading>
                                </div>

                                <div class="flex items-center gap-2">
                                    <flux:icon.check-circle class="text-green-600 dark:text-green-400"
                                        variant="solid" />
                                    <flux:heading>Optimización de palabras clave para ATS</flux:heading>
                                </div>

                                <div class="flex items-center gap-2">
                                    <flux:icon.check-circle class="text-green-600 dark:text-green-400"
                                        variant="solid" />
                                    <flux:heading>Mejoras de gramática y claridad</flux:heading>
                                </div>

                                <div class="flex items-center gap-2">
                                    <flux:icon.check-circle class="text-green-600 dark:text-green-400"
                                        variant="solid" />
                                    <flux:heading>Generador de cartas de presentación</flux:heading>
                                </div>

                                <div class="flex items-center gap-2">
                                    <flux:icon.check-circle class="text-green-600 dark:text-green-400"
                                        variant="solid" />
                                    <flux:heading>Puntuación y mejora de ATS</flux:heading>
                                </div>

                                <div class="flex items-center gap-2">
                                    <flux:icon.check-circle class="text-green-600 dark:text-green-400"
                                        variant="solid" />
                                    <flux:heading>Formato profesional</flux:heading>
                                </div>
                            </div>

                            <flux:button class="w-full" as="link"
                                href="{{ route('checkout.start', ['variant' => config('services.mercadopago.plans.yearly')]) }}"
                                variant="filled" icon-trailing="chevron-right">
                                Prueba gratis
                            </flux:button>
                        </div>
                    </flux:card>
                </div>
            </div>

            <flux:separator variant="subtle" />

            <div class="flex items-center justify-center gap-2 text-center">
                <flux:subheading>¿Aún no estás seguro? Mira qué dicen nuestros <flux:link class="!text-sm"
                        wire:navigate href="/customers">clientes satisfechos</flux:link>.</flux:subheading>
            </div>
        </div>
    </section>
</x-layouts.main>
