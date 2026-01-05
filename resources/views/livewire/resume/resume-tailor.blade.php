<div>
    <!-- Progress modal -->
    <flux:modal class="!max-w-sm flex flex-col items-center space-y-6 p-4" name="tailoring-in-progress"
        :dismissible="false" :closable="false" variant="floating">
        <div>
            <flux:heading size="lg" class="text-center">
                Tailoring your CV
            </flux:heading>
            <flux:subheading class="text-center">
                Matching your experience to the job description while preserving your original voice.
            </flux:subheading>
        </div>

        <flux:icon.loading />
    </flux:modal>

    <!-- Result modal -->
    <flux:modal name="tailoring-result" :dismissible="false" variant="floating" class="w-full! max-w-3xl space-y-6 p-4">
        <div>
            <flux:heading size="lg">
                Your tailored CV is ready
            </flux:heading>

            <flux:subheading>
                We optimized your CV based on the job description while keeping your original content intact.
            </flux:subheading>
        </div>

        <flux:editor wire:model.live="tailored"
            toolbar="heading | bold italic underline | bullet ordered | align ~ undo redo"
            placeholder="Edit your tailored CV..." class="[&_ [data-slot=content]]:min-h-[350px]!" />

        <div class="flex justify-end">
            <flux:button variant="primary" icon="arrow-down-tray" wire:click="downloadPdf">
                Download tailored PDF
            </flux:button>
        </div>
    </flux:modal>

    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Resume tailor') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('Tailor your resume to match any job description') }}
        </flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <div class="w-full py-6">
        <div class="flex flex-col lg:flex-row gap-6 w-full">
            <div class="w-full">
                <flux:file-upload wire:model="resume" label="Upload resume">
                    <flux:file-upload.dropzone heading="Drop your CV or click to browse" text="PDF up to 10MB"
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

            <div class="w-full">
                <flux:textarea wire:model="description" rows="10" label="Job description"
                    placeholder="We are looking for a Software Developer to join our company.

The right candidate must be..." />

                <flux:button x-on:click="$wire.startTailoring()" icon="sparkles" class="mt-4 w-full" variant="primary">
                    Tailor my CV
                </flux:button>
            </div>
        </div>
    </div>
</div>

@script
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('tailoring-started', async () => {

                $flux.modal('tailoring-in-progress').show();
                await new Promise(resolve => requestAnimationFrame(resolve));
                $wire.call('tailorResume');
            });
        });
    </script>
@endscript
