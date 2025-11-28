<div class="flex gap-6">
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
        <flux:button x-on:click="$flux.modal('tailoring-in-progress').show()" wire:click="tailorResume" icon="sparkles"
            class="mt-4 w-full" variant="primary">
            Tailor my CV
        </flux:button>
    </div>


    <!-- Progress modal -->
    <flux:modal name="tailoring-in-progress" :closeable="false">
        <div class="flex flex-col items-center gap-4 p-6">
            <flux:heading size="lg" class="text-center text-lg font-medium">Tailoring your CV, please wait...
            </flux:heading>
            <flux:icon.loading />
        </div>
    </flux:modal>

    <!-- Result modal -->
    <flux:modal name="tailoring-result" size="2xl">
        <div class="space-y-6">
            <flux:heading size="lg">
                Your tailored CV is ready ✨
            </flux:heading>

            <flux:subheading>
                We optimized your CV based on the job description while keeping your original content intact.
            </flux:subheading>

            <flux:editor wire:model.live="tailored" label="Your Tailored CV (editable)"
                toolbar="heading | bold italic underline | bullet ordered | align ~ undo redo"
                class="**:data-[slot=content]:min-h-[350px]!" />
            <div class="flex justify-end">
                <flux:button variant="primary" icon="arrow-down-tray" wire:click="downloadPdf">
                    Download tailored PDF
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
