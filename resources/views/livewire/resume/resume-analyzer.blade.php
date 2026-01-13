<div>
    <!-- Analyzing modal -->
    <flux:modal class="!max-w-sm flex flex-col items-center space-y-6 p-4" name="analyzing-in-progress"
        :dismissible="false" :closable="false" variant="floating">

        <div>
            <flux:heading size="lg" class="text-center">
                Analyzing your CV
            </flux:heading>

            <flux:subheading class="text-center">
                Reviewing your experience, skills, and structure to understand your profile.
            </flux:subheading>
        </div>

        <flux:icon.loading />
    </flux:modal>

    <!-- Score result modal -->
    <flux:modal class="!max-w-sm flex flex-col items-center space-y-6 p-4" name="analysis-result" variant="floating">
        <div>
            <flux:heading size="lg" class="text-center">
                Your ATS score
            </flux:heading>

            <flux:subheading class="text-center">
                This score reflects how well your CV matches common ATS requirements and job descriptions.
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
            Improve my resume
        </flux:button>
    </flux:modal>

    <!-- Improving modal -->
    <flux:modal class="!max-w-sm flex flex-col items-center space-y-6 p-4" name="improving-in-progress"
        :dismissible="false" :closable="false" variant="floating">

        <div>
            <flux:heading size="lg" class="text-center">
                Improving your CV
            </flux:heading>

            <flux:subheading class="text-center">
                Refining wording, highlighting key skills, and optimizing it for ATS systems.
            </flux:subheading>
        </div>

        <flux:icon.loading />
    </flux:modal>

    <!-- Result modal -->
    <flux:modal name="improving-result" :dismissible="false" variant="floating"
        class="w-full! max-w-3xl space-y-6 p-4">

        <div>
            <flux:heading size="lg">
                Your improved CV is ready
            </flux:heading>

            <flux:subheading>
                Your resume was enhanced to better match the job description while keeping your experience authentic.
            </flux:subheading>
        </div>

        <flux:editor wire:model.live="improved"
            toolbar="heading | bold italic underline | bullet ordered | align ~ undo redo"
            placeholder="Review or edit your improved CV..." class="[&_ [data-slot=content]]:min-h-[350px]!" />

        <div class="flex justify-end">
            <flux:button variant="primary" icon="arrow-down-tray" wire:click="downloadPdf">
                Download improved PDF
            </flux:button>
        </div>
    </flux:modal>

    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">
            {{ __('Resume analyzer') }}
        </flux:heading>

        <flux:subheading size="lg" class="mb-6">
            {{ __('Check your ATS score and improve your chances of getting hired') }}
        </flux:subheading>

        <flux:separator variant="subtle" />
    </div>

    <div class="w-full lg:w-1/2 py-6">
        <flux:file-upload wire:model="resume" label="Upload resume">
            <flux:file-upload.dropzone heading="Drop your CV or click to browse" text="PDF up to 10MB" with-progress
                inline />
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
            Analyze my CV
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
