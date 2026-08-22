<div class="mx-auto w-full max-w-5xl">
    <x-resume.working-overlay target="generate" heading="Adaptando tu currículum" :steps="[
        'Leemos tu CV y lo pasamos a datos estructurados.',
        'Extraemos los requisitos del aviso.',
        'Reescribimos tu experiencia para que responda a lo que piden.',
    ]" />

    @if (! $tailored)
        <form wire:submit="generate" class="grid gap-6 lg:grid-cols-2">
            <div class="space-y-4">
                <x-resume.upload-field :file="$form->resume" />
            </div>

            <div class="flex flex-col justify-between gap-4">
                <x-resume.job-description-field :value="$form->description" :rows="9" />

                <div class="space-y-2">
                    <flux:button type="submit" variant="primary" icon="sparkles" class="w-full">
                        {{ __('Probar gratis') }}
                    </flux:button>

                    <flux:text size="sm" class="text-center text-zinc-500">
                        @if ($remaining > 0)
                            {{ trans_choice('Te queda :count prueba gratis hoy|Te quedan :count pruebas gratis hoy', $remaining, ['count' => $remaining]) }}
                        @else
                            {{ __('Usaste tus pruebas gratis de hoy.') }}
                        @endif
                    </flux:text>
                </div>
            </div>
        </form>
    @else
        <div class="space-y-6">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <flux:heading size="lg">{{ __('Este es tu currículum adaptado') }}</flux:heading>
                    <flux:subheading>
                        {{ __('Creá una cuenta para editarlo campo por campo y descargarlo en PDF.') }}
                    </flux:subheading>
                </div>

                <div class="flex items-center gap-2">
                    <flux:button wire:click="startOver" icon="arrow-path" variant="ghost" size="sm">
                        {{ __('Probar con otro') }}
                    </flux:button>

                    <flux:button :href="route('register')" icon="arrow-down-tray" variant="primary">
                        {{ __('Descargar en PDF') }}
                    </flux:button>
                </div>
            </div>

            {{-- Vista en HTML y no el PDF real: la demo no guarda el documento,
                 así que no hay URL protegida desde donde servirlo. --}}
            <x-resume.preview :resume="$tailored" />

            <flux:callout icon="lock-closed" variant="secondary">
                <flux:callout.heading>{{ __('No guardamos este currículum') }}</flux:callout.heading>
                <flux:callout.text>
                    {{ __('El resultado de la prueba vive solo en esta pantalla. Si cerrás la pestaña, se pierde.') }}
                </flux:callout.text>
            </flux:callout>
        </div>
    @endif

    <flux:modal name="demo-limit" class="max-w-md space-y-6" x-on:demo-limit-reached.window="$flux.modal('demo-limit').show()">
        <div>
            <flux:heading size="lg">{{ __('Se te acabaron las pruebas gratis') }}</flux:heading>
            <flux:subheading>
                {{ __('Creá una cuenta para adaptar tu CV a todas las ofertas que quieras, editarlo y descargarlo en PDF.') }}
            </flux:subheading>
        </div>

        <div class="flex justify-end gap-2">
            <flux:modal.close>
                <flux:button variant="ghost">{{ __('Ahora no') }}</flux:button>
            </flux:modal.close>

            <flux:button :href="route('register')" variant="primary">{{ __('Crear cuenta') }}</flux:button>
        </div>
    </flux:modal>
</div>
