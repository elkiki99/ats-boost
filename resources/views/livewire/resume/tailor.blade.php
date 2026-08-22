<div>
    <x-resume.page-header title="{{ __('Adaptar currículum') }}"
        description="{{ __('Reescribimos tu CV con el vocabulario de la oferta, sin inventar experiencia que no tengas.') }}" />

    <x-resume.working-overlay target="generate" heading="Adaptando tu currículum" :steps="[
        'Leemos tu CV y lo pasamos a datos estructurados.',
        'Extraemos los requisitos y las palabras clave del aviso.',
        'Reescribimos cada logro para que responda a lo que piden.',
    ]" />

    <form wire:submit="generate" class="grid gap-6 lg:grid-cols-2">
        <flux:card class="space-y-6">
            <x-resume.upload-field :file="$form->resume" />
        </flux:card>

        <flux:card class="flex flex-col justify-between gap-6">
            <x-resume.job-description-field :value="$form->description" />

            <flux:button type="submit" variant="primary" icon="sparkles" class="w-full">
                {{ __('Adaptar mi CV') }}
            </flux:button>
        </flux:card>
    </form>

    <flux:callout icon="light-bulb" class="mt-6" variant="secondary">
        <flux:callout.heading>{{ __('Solo reordenamos y reescribimos lo que ya está en tu CV') }}</flux:callout.heading>
        <flux:callout.text>
            {{ __('Nunca agregamos tecnologías, empresas ni años de experiencia que no aparezcan en el archivo original. Un dato inventado se cae en la primera entrevista.') }}
        </flux:callout.text>
    </flux:callout>
</div>
