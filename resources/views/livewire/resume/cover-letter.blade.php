<div>
    <!-- Progress modal -->
    <flux:modal class="!max-w-sm flex flex-col items-center space-y-6 p-4" name="cover-letter-in-progress"
        :dismissible="false" :closable="false" variant="floating">
        <div>
            <flux:heading size="lg" class="text-center">
                Generando carta de presentación
            </flux:heading>
            <flux:subheading class="text-center">
                Combinando tu estilo para generar una carta de presentación ganadora...
            </flux:subheading>
        </div>

        <flux:icon.loading />
    </flux:modal>

    <!-- Result modal -->
    <flux:modal class="!max-w-sm flex flex-col items-center space-y-6 p-4" name="cover-letter-result"
        :dismissible="false" variant="floating" class="w-full! max-w-3xl space-y-6 p-4">
        <div>
            <flux:heading size="lg">
                Tu carta de presentación está lista
            </flux:heading>

            <flux:subheading>
                Creamos una carta de presentación personalizada según tus necesidades.
            </flux:subheading>
        </div>

        <flux:editor wire:model.live="coverLetter"
            toolbar="heading | bold italic underline | bullet ordered | align ~ undo redo"
            placeholder="Edit your cover letter..." class="[&_ [data-slot=content]]:min-h-[350px]!" />

        <div class="flex justify-end">
            <flux:button variant="primary" icon="arrow-down-tray" wire:click="downloadPdf">
                Descargar carta de presentación
            </flux:button>
        </div>
    </flux:modal>

    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Carta de presentación') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('Genera cartas de presentación en segundos y destaca') }}
        </flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <div class="flex flex-col lg:flex-row gap-6 w-full">
        <div class="w-full">
            <flux:textarea wire:model="company" rows="10" label="Empresa" badge="Opcional"
                placeholder="La misión de Meta es dar a las personas el poder de construir comunidades y acercar al mundo.

Nuestras tecnologías ayudan a las personas a conectarse, encontrar comunidades y hacer crecer negocios. Cuando Facebook se lanzó en 2004, cambió la..." />
        </div>

        <div class="w-full">
            <flux:textarea required autofocus wire:model="description" rows="10" label="Descripción del trabajo"
                placeholder="Estamos buscando un desarrollador de software para unirse a nuestra empresa.

El candidato ideal debe ser..." />

        </div>
    </div>

    <div class="w-full lg:w-1/2 mt-6">
        <flux:file-upload required wire:model="resume" label="Cargar currículum">
            <flux:file-upload.dropzone heading="Suelta tu CV o haz clic para examinar" text="PDF hasta 10MB"
                with-progress inline />
        </flux:file-upload>

        <div class="mt-3 flex flex-col gap-2">
            @if ($resume)
                <flux:file-item heading="{{ $resume->getClientOriginalName() }}">
                    <x-slot name="actions">
                        <flux:file-item.remove wire:click="$set('resume', null)" />
                    </x-slot>
                </flux:file-item>
            @endif
        </div>
    </div>

    <flux:button x-on:click="$wire.startGeneratingCoverLetter()" icon="pencil-square" class="mt-4 w-full"
        variant="primary">
        Generar carta de presentación
    </flux:button>
</div>

@script
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('cover-letter-started', async () => {

                $flux.modal('cover-letter-in-progress').show();
                await new Promise(resolve => requestAnimationFrame(resolve));
                $wire.call('coverLetterResume');
            });
        });
    </script>
@endscript
