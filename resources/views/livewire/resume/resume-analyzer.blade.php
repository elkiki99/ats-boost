<div>
    <!-- Analyzing modal -->
    <flux:modal class="!max-w-sm flex flex-col items-center space-y-6 p-4" name="analyzing-in-progress"
        :dismissible="false" :closable="false" variant="floating">

        <div>
            <flux:heading size="lg" class="text-center">
                Analizando tu CV
            </flux:heading>

            <flux:subheading class="text-center">
                Revisando tu experiencia, habilidades y estructura para entender tu perfil.
            </flux:subheading>
        </div>

        <flux:icon.loading />
    </flux:modal>

    <!-- Score result modal -->
    <flux:modal class="!max-w-sm flex flex-col items-center space-y-6 p-4" :dismissible="false" name="analysis-result"
        variant="floating">
        <div>
            <flux:heading size="lg" class="text-center">
                Tu puntuación de ATS
            </flux:heading>

            <flux:subheading class="text-center">
                Esta puntuación refleja qué tan bien tu CV coincide con los requisitos comunes de ATS y las
                descripciones de trabajo.
            </flux:subheading>
        </div>

        <!-- acá iría el score visual (número, barra, etc.) -->
        <div wire:key="score-circle-{{ $score }}" x-data="scoreCircle({{ $score }})"
            class="relative mb-6 flex items-center justify-center">
            <svg width="140" height="140" class="-rotate-90">
                <!-- background -->
                <circle cx="70" cy="70" r="52" stroke="#e5e7eb" stroke-width="10" fill="transparent" />

                <!-- progress -->
                <circle cx="70" cy="70" r="52" :stroke="color" stroke-width="10" fill="transparent"
                    stroke-linecap="round" :stroke-dasharray="circumference" :stroke-dashoffset="offset"
                    class="transition-all duration-700 ease-out" />
            </svg>

            <!-- score -->
            <div class="absolute text-3xl font-bold text-zinc-800 dark:text-white">
                <span x-text="score"></span>
            </div>
        </div>

        <flux:button variant="primary" icon="rocket-launch" wire:click="startImproving">
            Mejorar mi currículum
        </flux:button>
    </flux:modal>

    <!-- Improving modal -->
    <flux:modal class="!max-w-sm flex flex-col items-center space-y-6 p-4" name="improving-in-progress"
        :dismissible="false" :closable="false" variant="floating">

        <div>
            <flux:heading size="lg" class="text-center">
                Mejorando tu CV
            </flux:heading>

            <flux:subheading class="text-center">
                Refinando la redacción, destacando habilidades clave y optimizándolo para sistemas ATS.
            </flux:subheading>
        </div>

        <flux:icon.loading />
    </flux:modal>

    <!-- Result modal -->
    <flux:modal name="improving-result" :dismissible="false" variant="floating"
        class="w-full! max-w-3xl space-y-6 p-4">

        <div>
            <flux:heading size="lg">
                Tu CV mejorado está listo
            </flux:heading>

            <flux:subheading>
                Tu currículum fue mejorado para que coincida mejor con la descripción del trabajo mientras se mantiene
                tu experiencia auténtica.
            </flux:subheading>
        </div>

        <flux:editor wire:model.live="improved"
            toolbar="heading | bold italic underline | bullet ordered | align ~ undo redo"
            placeholder="Review or edit your improved CV..." class="[&_ [data-slot=content]]:min-h-[350px]!" />

        <div class="flex justify-end">
            <flux:button variant="primary" icon="arrow-down-tray" wire:click="downloadPdf">
                Descargar CV mejorado
            </flux:button>
        </div>
    </flux:modal>

    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">
            {{ __('Analizador de currículum') }}
        </flux:heading>

        <flux:subheading size="lg" class="mb-6">
            {{ __('Verifica tu puntuación de ATS y mejora tus posibilidades de ser contratado') }}
        </flux:subheading>

        <flux:separator variant="subtle" />
    </div>

    <div class="w-full lg:w-1/2 py-6">
        <flux:file-upload wire:model="resume" label="Cargar currículum">
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

        <flux:button x-on:click="$wire.startAnalyzing()" icon="magnifying-glass" class="mt-4 w-full" variant="primary">
            Analizar mi CV
        </flux:button>
    </div>
</div>

@script
    <script>
        document.addEventListener('livewire:initialized', () => {

            Livewire.on('analysis-started', async () => {
                $flux.modal('analyzing-in-progress').show()

                await new Promise(r => requestAnimationFrame(r))
                $wire.call('analyzeResume')
            })

            Livewire.on('analysis-finished', () => {
                $flux.modal('analyzing-in-progress').close()
                $flux.modal('analysis-result').show()
            })

            Livewire.on('improving-started', async () => {
                $flux.modal('analysis-result').close()
                $flux.modal('improving-in-progress').show()

                await new Promise(r => requestAnimationFrame(r))
                $wire.call('improveResume')
            })

            Livewire.on('improving-finished', () => {
                $flux.modal('improving-in-progress').close()
                $flux.modal('improving-result').show()
            })

            Alpine.data('scoreCircle', (score) => ({
                score: Number(score),
                radius: 52,
                circumference: 2 * Math.PI * 52,
                get offset() {
                    return this.circumference * (1 - this.score / 100)
                },
                get color() {
                    if (this.score < 50) return '#ef4444'
                    if (this.score < 75) return '#f59e0b'
                    return '#22c55e'
                }
            }))
        })
    </script>
@endscript
