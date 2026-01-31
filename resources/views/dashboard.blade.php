<x-layouts.app :title="__('Panel de control • ATS Boost')">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Panel de control') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('Bienvenido de nuevo') }} {{ auth()->user()->name }}! Vamos a
            potenciar tus solicitudes de empleo.
        </flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <div class="flex h-auto w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid gap-4 md:grid-cols-3 items-stretch">
            {{-- Tailor Your Resume (core feature) --}}
            <a href="{{ route('resume.resume-tailor') }}">
                <flux:card
                    class="h-full group cursor-pointer transition hover:border-neutral-300 dark:hover:border-neutral-600">

                    <div class="mb-2 flex items-center">
                        <flux:heading>Adapta tu currículum</flux:heading>
                        <flux:spacer />
                        <flux:icon.arrow-right variant="micro"
                            class="text-zinc-500 dark:text-zinc-300 transition-transform duration-300 group-hover:translate-x-1" />
                    </div>

                    <flux:subheading>
                        Adapta tu currículum a cualquier descripción de trabajo. Optimízalo para el éxito en ATS.
                    </flux:subheading>
                </flux:card>
            </a>

            {{-- Resume Analyzer --}}
            <a href="{{ route('resume.resume-analyzer') }}">
                <flux:card
                    class="h-full group cursor-pointer transition hover:border-neutral-300 dark:hover:border-neutral-600">

                    <div class="mb-2 flex items-center">
                        <flux:heading>Analizador de currículum</flux:heading>
                        <flux:spacer />
                        <flux:icon.arrow-right variant="micro"
                            class="text-zinc-500 dark:text-zinc-300 transition-transform duration-300 group-hover:translate-x-1" />
                    </div>

                    <flux:subheading>
                        Obtén retroalimentación instantánea, puntuación de ATS y mejoras accionables para tu currículum.
                    </flux:subheading>
                </flux:card>
            </a>

            {{-- Cover Letter --}}
            <a href="{{ route('resume.cover-letter') }}">
                <flux:card
                    class="h-full group cursor-pointer transition hover:border-neutral-300 dark:hover:border-neutral-600">

                    <div class="mb-2 flex items-center">
                        <flux:heading>Carta de presentación</flux:heading>
                        <flux:spacer />
                        <flux:icon.arrow-right variant="micro"
                            class="text-zinc-500 dark:text-zinc-300 transition-transform duration-300 group-hover:translate-x-1" />
                    </div>

                    <flux:subheading>
                        Genera una carta de presentación basada en estándares profesionales comprobados.
                    </flux:subheading>
                </flux:card>
            </a>
        </div>

        {{-- <div
            class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
        </div> --}}
    </div>
</x-layouts.app>
