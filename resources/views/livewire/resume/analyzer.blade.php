<div>
    <x-resume.page-header title="{{ __('Analizar currículum') }}"
        description="{{ __('Qué puntaje saca tu CV frente a un filtro automático, y exactamente qué corregir.') }}" />

    <x-resume.working-overlay target="analyze" heading="Analizando tu currículum" :steps="[
        'Extraemos el texto tal como lo lee un ATS.',
        'Evaluamos estructura, redacción y densidad de palabras clave.',
    ]" />

    <x-resume.working-overlay target="improve" heading="Reescribiendo tu currículum" :steps="[
        'Corregimos cada problema detectado.',
        'Volvemos a puntuarlo para medir la mejora.',
    ]" />

    <div class="grid gap-6 lg:grid-cols-5">
        <form wire:submit="analyze" class="lg:col-span-2">
            <flux:card class="space-y-6">
                <x-resume.upload-field :file="$form->resume" />

                <flux:button type="submit" variant="primary" icon="chart-bar" class="w-full">
                    {{ __('Analizar mi CV') }}
                </flux:button>
            </flux:card>
        </form>

        <div class="lg:col-span-3">
            @if (! $ats)
                <flux:card
                    class="flex h-full min-h-72 flex-col items-center justify-center gap-3 text-center">
                    <flux:icon.document-magnifying-glass class="size-10 text-zinc-300 dark:text-zinc-600" />
                    <div>
                        <flux:heading>{{ __('Todavía no analizamos nada') }}</flux:heading>
                        <flux:subheading>
                            {{ __('Subí tu currículum y te devolvemos el puntaje con los problemas concretos que encontramos.') }}
                        </flux:subheading>
                    </div>
                </flux:card>
            @else
                <flux:card class="space-y-6">
                    <div class="flex items-start justify-between gap-6">
                        <div>
                            <flux:heading size="lg">{{ __('Compatibilidad con ATS') }}</flux:heading>
                            <flux:subheading>{{ $ats->verdict() }}</flux:subheading>
                        </div>

                        <div class="text-end">
                            <p @class([
                                'text-4xl font-bold tabular-nums leading-none',
                                'text-green-600 dark:text-green-400' => $ats->tone() === 'green',
                                'text-amber-600 dark:text-amber-400' => $ats->tone() === 'amber',
                                'text-red-600 dark:text-red-400' => $ats->tone() === 'red',
                            ])>{{ $ats->score }}</p>
                            <p class="text-xs text-zinc-500">/ 100</p>
                        </div>
                    </div>

                    @if ($ats->breakdown)
                        <div class="space-y-2.5">
                            @foreach ($ats->breakdown as $dimension => $value)
                                <div>
                                    <div class="mb-1 flex items-center justify-between text-xs">
                                        <span class="text-zinc-600 dark:text-zinc-400">
                                            {{-- El modelo puede sumar dimensiones nuevas; si no hay
                                                 traducción, se muestra la clave en vez de romper. --}}
                                            {{ __("resume.ats.{$dimension}") === "resume.ats.{$dimension}"
                                                ? Str::headline($dimension)
                                                : __("resume.ats.{$dimension}") }}
                                        </span>
                                        <span class="tabular-nums text-zinc-500">{{ $value }}</span>
                                    </div>
                                    <div class="h-1.5 overflow-hidden rounded-full bg-zinc-200 dark:bg-zinc-700">
                                        <div class="h-full rounded-full bg-accent" style="width: {{ $value }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if ($ats->strengths)
                        <div>
                            <flux:heading size="sm" class="mb-2">{{ __('Lo que ya está bien') }}</flux:heading>
                            <ul class="space-y-1.5 text-sm">
                                @foreach ($ats->strengths as $strength)
                                    <li class="flex items-start gap-2">
                                        <flux:icon.check-circle variant="micro"
                                            class="mt-0.5 shrink-0 text-green-600 dark:text-green-400" />
                                        <span>{{ $strength }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if ($ats->issues)
                        <div>
                            <flux:heading size="sm" class="mb-2">{{ __('Qué conviene corregir') }}</flux:heading>

                            <flux:accordion transition>
                                @foreach ($ats->issues as $issue)
                                    <flux:accordion.item>
                                        <flux:accordion.heading>
                                            <span class="flex items-center gap-2">
                                                <flux:badge size="sm" :color="match ($issue['severity']) {
                                                    'high' => 'red',
                                                    'medium' => 'amber',
                                                    default => 'zinc',
                                                }">
                                                    {{ __(match ($issue['severity']) {
                                                        'high' => 'Alto',
                                                        'medium' => 'Medio',
                                                        default => 'Bajo',
                                                    }) }}
                                                </flux:badge>
                                                {{ $issue['title'] }}
                                            </span>
                                        </flux:accordion.heading>
                                        <flux:accordion.content>{{ $issue['detail'] }}</flux:accordion.content>
                                    </flux:accordion.item>
                                @endforeach
                            </flux:accordion>
                        </div>
                    @endif

                    @if ($ats->missingKeywords)
                        <div>
                            <flux:heading size="sm" class="mb-2">{{ __('Términos ausentes') }}</flux:heading>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach ($ats->missingKeywords as $keyword)
                                    <flux:badge size="sm" color="zinc">{{ $keyword }}</flux:badge>
                                @endforeach
                            </div>
                            <flux:description class="mt-2">
                                {{ __('Agregalos solo si realmente los manejás.') }}
                            </flux:description>
                        </div>
                    @endif

                    <flux:separator variant="subtle" />

                    <flux:button wire:click="improve" variant="primary" icon="arrow-trending-up" class="w-full">
                        {{ __('Corregir y reescribir mi CV') }}
                    </flux:button>
                </flux:card>
            @endif
        </div>
    </div>
</div>
