<div class="w-full py-6">
    <div class="flex flex-col lg:flex-row gap-6 w-full">
        <div class="w-full">
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

        <div class="w-full">
            <flux:textarea wire:model="description" rows="10" label="Job description"
                placeholder="We are looking for a Software Developer to join our company.

The right candidate must be..." />

            <flux:button x-on:click="$wire.startTailoring()" icon="sparkles" class="mt-4 w-full" variant="primary">
                Tailor my CV
            </flux:button>
        </div>
    </div>

    <!-- Progress modal -->
    <flux:modal name="tailoring-in-progress" :dismissible="false" :closable="false" variant="floating">
        <div class="flex flex-col items-center gap-4 p-6">
            <flux:heading size="lg" class="text-center text-lg font-medium">Tailoring your CV, please wait...
            </flux:heading>
            <flux:icon.loading />
        </div>
    </flux:modal>

    <!-- Result modal -->
    <flux:modal name="tailoring-result" :dismissible="false" variant="floating" class="w-full! max-w-3xl">
        <div class="space-y-6">
            <flux:heading size="lg">
                Your tailored CV is ready ✨
            </flux:heading>

            <flux:subheading>
                We optimized your CV based on the job description while keeping your original content intact.
            </flux:subheading>

            <flux:editor wire:model.live="tailored"
                toolbar="heading | bold italic underline | bullet ordered | align ~ undo redo"
                placeholder="Edit your tailored CV..." class="[&_ [data-slot=content]]:min-h-[350px]!" />

            <div class="flex justify-end">
                <flux:button variant="primary" icon="arrow-down-tray" wire:click="downloadPdf">
                    Download tailored PDF
                </flux:button>
            </div>
        </div>
    </flux:modal>

    <flux:modal name="limit-modal" variant="floating">
        <div class="space-y-4 p-6 text-center">
            <flux:heading size="lg">Limit reached 🚀</flux:heading>

            <flux:subheading>
                You've reached the free limit. Create an account or upgrade to continue.
            </flux:subheading>

            <div class="flex justify-center gap-2 mt-4">
                <flux:button variant="primary" href="/register">Create account</flux:button>
                <flux:button href="/pricing">See premium plans</flux:button>
            </div>
        </div>
    </flux:modal>
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
