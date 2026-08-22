@props([
    'model' => 'form.resume',
    'file' => null,
    'label' => 'Tu currículum actual',
])

{{--
    Campo de carga del currículum.

    Vive en un componente porque las tres pantallas del panel y la demo
    pública lo usan igual. Antes el mismo bloque estaba copiado cuatro veces
    y ya había divergido: en dos vistas decía "PDF hasta 10MB" y en las otras
    aceptaba TXT sin decirlo.
--}}
<flux:field>
    <flux:label>{{ $label }}</flux:label>

    <flux:file-upload wire:model="{{ $model }}" accept=".pdf,.txt">
        <flux:file-upload.dropzone heading="Soltá tu CV o hacé clic para buscarlo"
            text="PDF o TXT, hasta {{ (int) (config('resume.limits.upload_kilobytes') / 1024) }} MB" with-progress
            inline />
    </flux:file-upload>

    @if ($file)
        <div class="mt-3">
            <flux:file-item heading="{{ $file->getClientOriginalName() }}"
                subheading="{{ number_format($file->getSize() / 1024, 0, ',', '.') }} KB">
                <x-slot name="actions">
                    <flux:file-item.remove wire:click="$set('{{ $model }}', null)" />
                </x-slot>
            </flux:file-item>
        </div>
    @endif

    <flux:error name="{{ $model }}" />

    <flux:description>
        Tiene que ser un PDF con texto seleccionable. Si es un escaneo o una foto, no vamos a poder leerlo.
    </flux:description>
</flux:field>
