<div>
    <!-- Progress modal -->
    <flux:modal class="!max-w-sm flex flex-col items-center space-y-6 p-4" name="cover-letter-in-progress"
        :dismissible="false" :closable="false" variant="floating">
        <div>
            <flux:heading size="lg" class="text-center">
                Generating cover letter
            </flux:heading>
            <flux:subheading class="text-center">
                Matching your vibe to generate a winning cover letter...
            </flux:subheading>
        </div>

        <flux:icon.loading />
    </flux:modal>

    <!-- Result modal -->
    <flux:modal class="!max-w-sm flex flex-col items-center space-y-6 p-4" name="cover-letter-result" :dismissible="false" variant="floating"
        class="w-full! max-w-3xl space-y-6 p-4">
        <div>
            <flux:heading size="lg">
                Your cover letter is ready
            </flux:heading>

            <flux:subheading>
                We created a tailored cover letter based on your needs.
            </flux:subheading>
        </div>

        <flux:editor wire:model.live="coverLetter"
            toolbar="heading | bold italic underline | bullet ordered | align ~ undo redo"
            placeholder="Edit your cover letter..." class="[&_ [data-slot=content]]:min-h-[350px]!" />

        <div class="flex justify-end">
            <flux:button variant="primary" icon="arrow-down-tray" wire:click="downloadPdf">
                Download cover letter
            </flux:button>
        </div>
    </flux:modal>

    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Cover letter') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('Generate cover letters in seconds and stand out') }}
        </flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <div class="flex flex-col lg:flex-row gap-6 w-full">
        <div class="w-full">
            <flux:textarea wire:model="company" rows="10" label="Company (optional)"
                placeholder="Meta's mission is to build the future of human connection and the technology that makes it possible.

Our technologies help people connect, find communities, and grow businesses. When Facebook launched in 2004, it changed the..." />
        </div>

        <div class="w-full">
            <flux:textarea wire:model="description" rows="10" label="Job description"
                placeholder="We are looking for a Software Developer to join our company.

The right candidate must be..." />

        </div>
    </div>

    <div class="w-full lg:w-1/2 mt-4">
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
    </div>

    <flux:button x-on:click="$wire.startGeneratingCoverLetter()" icon="pencil-square" class="mt-4 w-full" variant="primary">
        Generate cover letter
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
