@props([
    'model' => 'form.description',
    'value' => '',
    'rows' => 12,
])

<flux:field>
    <flux:label>Descripción del puesto</flux:label>

    <flux:textarea wire:model.blur="{{ $model }}" rows="{{ $rows }}"
        placeholder="Pegá acá el aviso completo: responsabilidades, requisitos y tecnologías.&#10;&#10;Cuanto más completo esté, mejor va a quedar la adaptación." />

    <flux:error name="{{ $model }}" />

    {{-- El contador es la señal más directa de que el aviso quedó corto: el
         mensaje de validación recién aparece al enviar. --}}
    <flux:description class="flex items-center justify-between">
        <span>Pegá el aviso entero, no solo el título.</span>
        <span @class([
            'tabular-nums',
            'text-amber-600 dark:text-amber-400' => mb_strlen((string) $value) > 0 && mb_strlen((string) $value) < 80,
        ])>
            {{ mb_strlen((string) $value) }} / 80 mín.
        </span>
    </flux:description>
</flux:field>
