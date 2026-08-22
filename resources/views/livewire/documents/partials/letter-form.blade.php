<div class="space-y-4">
    <flux:card class="space-y-4">
        <flux:heading size="sm">{{ __('Encabezado') }}</flux:heading>

        <flux:input wire:model.blur="letterForm.data.candidate_name" :label="__('Nombre y apellido')" />

        <div class="grid gap-4 sm:grid-cols-2">
            <flux:input wire:model.blur="letterForm.data.role" :label="__('Puesto')" />
            <flux:input wire:model.blur="letterForm.data.company" :label="__('Empresa')" />
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <flux:input wire:model.blur="letterForm.data.contact.location" :label="__('Ubicación')" />
            <flux:input wire:model.blur="letterForm.data.contact.phone" :label="__('Teléfono')" />
        </div>

        <flux:input wire:model.blur="letterForm.data.contact.email" type="email" :label="__('Correo')" />
    </flux:card>

    <flux:card class="space-y-4">
        <flux:heading size="sm">{{ __('Cuerpo') }}</flux:heading>

        <flux:input wire:model.blur="letterForm.data.greeting" :label="__('Saludo')"
            placeholder="Estimado equipo de Multiline Contact Center:" />

        <flux:field>
            <flux:label>{{ __('Párrafos') }}</flux:label>

            <div class="space-y-3">
                @foreach ($letterForm->data['paragraphs'] ?? [] as $index => $paragraph)
                    <div class="flex items-start gap-2" wire:key="par-{{ $index }}">
                        <flux:textarea wire:model.blur="letterForm.data.paragraphs.{{ $index }}" rows="4"
                            class="flex-1" />

                        <div class="flex flex-col gap-1">
                            <flux:button wire:click="moveParagraph({{ $index }}, -1)" icon="chevron-up"
                                size="xs" variant="subtle" :disabled="$index === 0" />
                            <flux:button wire:click="moveParagraph({{ $index }}, 1)" icon="chevron-down"
                                size="xs" variant="subtle"
                                :disabled="$index === count($letterForm->data['paragraphs'] ?? []) - 1" />
                            <flux:button wire:click="removeParagraph({{ $index }})" icon="trash"
                                size="xs" variant="subtle" />
                        </div>
                    </div>
                @endforeach
            </div>

            <flux:error name="letterForm.data.paragraphs" />

            <flux:button wire:click="addParagraph" icon="plus" size="sm" variant="ghost" class="mt-2">
                {{ __('Agregar párrafo') }}
            </flux:button>
        </flux:field>

        <flux:input wire:model.blur="letterForm.data.closing" :label="__('Despedida')" placeholder="Saludos cordiales," />
    </flux:card>
</div>
