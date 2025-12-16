<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid gap-4 md:grid-cols-3 items-stretch">

            {{-- Resume Builder --}}
            <a href="{{ route('dashboard.resume-builder') }}" wire:navigate>
                <flux:card
                    class="h-full group cursor-pointer transition hover:border-neutral-300 dark:hover:border-neutral-600">

                    <div class="mb-2 flex items-center">
                        <flux:heading>Resume builder</flux:heading>
                        <flux:spacer />
                        <flux:icon.arrow-right variant="micro"
                            class="text-zinc-500 dark:text-zinc-300 transition-transform duration-300 group-hover:translate-x-1" />
                    </div>

                    <flux:subheading>
                        Build a professional resume from scratch using clean layouts and smart suggestions.
                    </flux:subheading>
                </flux:card>
            </a>

            {{-- Tailor Your Resume (core feature) --}}
            <a href="{{ route('dashboard.resume-tailor') }}" wire:navigate>
                <flux:card
                    class="h-full group cursor-pointer transition hover:border-neutral-300 dark:hover:border-neutral-600">

                    <div class="mb-2 flex items-center">
                        <flux:heading>Tailor your resume</flux:heading>
                        <flux:spacer />
                        <flux:icon.arrow-right variant="micro"
                            class="text-zinc-500 dark:text-zinc-300 transition-transform duration-300 group-hover:translate-x-1" />
                    </div>

                    <flux:subheading>
                        Match your resume to any job description. Optimize it for ATS success.
                    </flux:subheading>
                </flux:card>
            </a>

            {{-- Resume Analyzer --}}
            <a href="{{ route('dashboard.resume-analyzer') }}" wire:navigate>
                <flux:card
                    class="h-full group cursor-pointer transition hover:border-neutral-300 dark:hover:border-neutral-600">

                    <div class="mb-2 flex items-center">
                        <flux:heading>Resume analyzer</flux:heading>
                        <flux:spacer />
                        <flux:icon.arrow-right variant="micro"
                            class="text-zinc-500 dark:text-zinc-300 transition-transform duration-300 group-hover:translate-x-1" />
                    </div>

                    <flux:subheading>
                        Get instant feedback, ATS score and actionable improvements for your resume.
                    </flux:subheading>
                </flux:card>
            </a>
        </div>

        {{-- Future / analytics / activity --}}
        <div
            class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
        </div>
    </div>
</x-layouts.app>
