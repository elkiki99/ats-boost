<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">
    <flux:main container>
        <div class="space-y-6 my-6">
            <div>
                <flux:heading class="text-6xl!">
                    Struggling to get hired? Boost your<br> ATS score by tailoring your CV to<br> finally land your
                    dream job
                </flux:heading>

                <flux:subheading class="text-lg!">
                    Just paste the job offer you want to apply to, upload your resume, and download your <br>tailored CV! Our engine simply reshapes what's already in your resume to better <br>match the role,
                    changing as little as possible while maximizing relevance.<br><br>
                </flux:subheading>
            </div>

            <div class="flex gap-6">
                <div class="w-full">
                    <flux:file-upload wire:model="resume" label="Upload resume">
                        <flux:file-upload.dropzone heading="Drop your CV or click to browse" text="PDF up to 10MB"
                            inline />
                    </flux:file-upload>

                    <div class="mt-3 flex flex-col gap-2">
                        <flux:file-item heading="Resume">
                            <x-slot name="actions">
                                <flux:file-item.remove />
                            </x-slot>
                        </flux:file-item>
                    </div>
                </div>

                <div class="w-full">
                    <flux:textarea rows="10" label="Job description"
                        placeholder="We are looking for a Software Developer to join our company.

The right candidate must be..." />
                </div>
            </div>

            <flux:subheading class="text-lg!">

                The template is based on a curated Harvard-style layout, ensuring<br>
                you look professional and stand out to recruiters.<br><br>

                And the best part? It's completely free — no credit card needed.
            </flux:subheading>
        </div>
    </flux:main>
    @fluxScripts
</body>

</html>
