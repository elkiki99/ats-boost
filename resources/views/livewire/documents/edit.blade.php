<div x-data
    {{-- La descarga la dispara el navegador, no Livewire: así el PDF llega
         por su propia URL, con la política del documento aplicada. --}}
    x-on:download-ready.window="window.location.href = $event.detail.url">

    <div class="mb-6 flex flex-wrap items-end justify-between gap-4">
        <div>
            <flux:button :href="route('documents.index')" icon="arrow-left" size="sm" variant="ghost"
                class="mb-2 -ms-2" wire:navigate>
                {{ __('Mis documentos') }}
            </flux:button>

            <flux:heading size="xl" level="1">{{ $document->title }}</flux:heading>

            <flux:subheading class="flex items-center gap-2">
                <flux:badge size="sm" color="zinc">{{ $document->type->label() }}</flux:badge>
                <span>{{ __('Actualizado') }} {{ $document->updated_at->diffForHumans() }}</span>
            </flux:subheading>
        </div>

        <div class="flex items-center gap-2">
            @if ($this->isResume())
                <flux:select wire:model.live="resumeForm.template" size="sm" class="w-40">
                    @foreach ($this->templateOptions() as $value => $label)
                        <flux:select.option value="{{ $value }}">{{ $label }}</flux:select.option>
                    @endforeach
                </flux:select>
            @endif

            <flux:button wire:click="save" icon="check" variant="filled">{{ __('Guardar') }}</flux:button>

            <flux:button wire:click="saveAndDownload" icon="arrow-down-tray" variant="primary">
                {{ __('Descargar PDF') }}
            </flux:button>
        </div>
    </div>

    <div class="grid gap-6 xl:grid-cols-2">
        <div>
            @if ($this->isResume())
                @include('livewire.documents.partials.resume-form')
            @else
                @include('livewire.documents.partials.letter-form')
            @endif
        </div>

        <div class="xl:sticky xl:top-6 xl:h-[calc(100vh-6rem)]">
            <flux:card class="flex h-full flex-col gap-3 p-3">
                <div class="flex items-center justify-between px-1">
                    <flux:heading size="sm">{{ __('Vista previa') }}</flux:heading>
                    <flux:text size="sm" class="text-zinc-500">
                        {{ __('Es el PDF real, no una aproximación.') }}
                    </flux:text>
                </div>

                {{-- previewVersion cambia en cada guardado y fuerza al
                     navegador a volver a pedir el PDF en vez de servir el
                     que ya tenía cacheado. --}}
                <iframe src="{{ route('documents.preview', $document) }}?v={{ $previewVersion }}"
                    title="{{ __('Vista previa del documento') }}"
                    class="h-full min-h-[70vh] w-full rounded-lg border border-zinc-200 bg-zinc-100 dark:border-zinc-700 dark:bg-zinc-800"
                    wire:loading.class="opacity-50" wire:target="save,saveAndDownload"></iframe>
            </flux:card>
        </div>
    </div>
</div>
