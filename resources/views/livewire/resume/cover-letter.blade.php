<div>
    <x-resume.page-header title="{{ __('Carta de presentación') }}"
        description="{{ __('Una carta breve y concreta, escrita a partir de hechos reales de tu CV.') }}" />

    <x-resume.working-overlay target="generate" heading="Escribiendo tu carta" :steps="[
        'Leemos tu CV y la oferta.',
        'Elegimos los hechos que responden a lo que piden.',
        'Redactamos la carta con el encabezado de tu CV.',
    ]" />

    <form wire:submit="generate" class="grid gap-6 lg:grid-cols-2">
        <flux:card class="space-y-6">
            <x-resume.upload-field :file="$form->resume" />

            <flux:field>
                <flux:label badge="{{ __('Opcional') }}">{{ __('Empresa destinataria') }}</flux:label>
                <flux:input wire:model.blur="form.company" placeholder="Multiline Contact Center" />
                <flux:error name="form.company" />
                <flux:description>
                    {{ __('Si la completás, la carta se dirige a la empresa por su nombre en lugar de usar un encabezado neutro.') }}
                </flux:description>
            </flux:field>
        </flux:card>

        <flux:card class="flex flex-col justify-between gap-6">
            <x-resume.job-description-field :value="$form->description" />

            <flux:button type="submit" variant="primary" icon="envelope" class="w-full">
                {{ __('Escribir mi carta') }}
            </flux:button>
        </flux:card>
    </form>
</div>
