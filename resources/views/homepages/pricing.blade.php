<x-layouts.main title="Pricing • ATS Boost">
    <section class="space-y-6">
        <div class="!text-center lg:!text-start">
            <flux:badge color="blue" icon="sparkles" size="sm" variant="pill">
                High impact, low cost
            </flux:badge>
        </div>

        <flux:heading level="1"
            class="!text-5xl md:!text-6xl font-black max-w-4xl mx-auto lg:mx-0 lg:max-w-full text-center lg:text-start">
            Ready for a job-winning resume<br class="hidden lg:block">
            that gets you <span
                class="text-5xl font-black text-transparent md:text-6xl dark:from-blue-500 dark:via-blue-300 dark:to-blue-600 bg-gradient-to-r from-blue-600 via-blue-400 to-blue-700 bg-clip-text">
                hired asap?</span>
        </flux:heading>

        <flux:subheading level="2"
            class="max-w-xl mx-auto text-sm text-center md:text-base lg:text-start lg:mx-0">
            Tailor your resume with ATS-friendly optimization to stand out from the crowd,<br class="hidden lg:block">
            get more recruiter calls, and unlock better job opportunities.
        </flux:subheading>

        <div class="py-6 space-y-6">
            <!-- Pricing -->
            <div class="flex flex-col w-full max-w-md gap-6 py-6 mx-auto lg:flex-row lg:max-w-none lg:gap-0">

                <!-- Weekly Plan -->
                <div
                    class="flex flex-col flex-1 hover:-translate-y-[2px] w-full gap-2 p-2 border rounded-2xl border-zinc-200 dark:border-zinc-700/75 bg-zinc-100 dark:bg-zinc-900 lg:mt-10 lg:pr-0 lg:border-r-0 lg:rounded-r-none">

                    <flux:card class="flex flex-col h-full p-6  rounded-lg shadow-sm md:p-8 lg:rounded-r-none">
                        <div class="space-y-6">

                            <div class="space-y-2">
                                <flux:subheading class="!text-sm">Weekly Plan</flux:subheading>
                                <flux:heading class="!text-3xl">$1.99 / week</flux:heading>
                            </div>

                            <!-- Features -->
                            <div class="flex flex-col gap-2">

                                <div class="flex items-center gap-2">
                                    <flux:icon.fire class="text-amber-600 dark:text-amber-400" variant="solid" />
                                    <flux:heading>Unlimited tailored resumes</flux:heading>
                                </div>

                                <div class="flex items-center gap-2">
                                    <flux:icon.check-circle class="text-green-600 dark:text-green-400"
                                        variant="solid" />
                                    <flux:heading>ATS keyword optimization</flux:heading>
                                </div>

                                <div class="flex items-center gap-2">
                                    <flux:icon.check-circle class="text-green-600 dark:text-green-400"
                                        variant="solid" />
                                    <flux:heading>Grammar & clarity enhancements</flux:heading>
                                </div>

                                <div class="flex items-center gap-2">
                                    <flux:icon.check-circle class="text-green-600 dark:text-green-400"
                                        variant="solid" />
                                    <flux:heading>Professional formatting</flux:heading>
                                </div>

                                <div class="flex items-center gap-2">
                                    <flux:icon.check-circle class="text-green-600 dark:text-green-400"
                                        variant="solid" />
                                    <flux:heading>Cover letter optimization</flux:heading>
                                </div>

                                <div class="flex items-center gap-2">
                                    <flux:icon.check-circle class="text-green-600 dark:text-green-400"
                                        variant="solid" />
                                    <flux:heading>Priority delivery</flux:heading>
                                </div>

                            </div>
                            <flux:button class="w-full" as="link" href="{{ route('checkout', 1139077) }}"
                                variant="filled" icon-trailing="chevron-right">
                                Try for free
                            </flux:button>
                        </div>
                    </flux:card>
                </div>

                <!-- Monthly Plan (Most Popular) -->
                <div
                    class="flex hover:-translate-y-[2px] flex-col flex-1 gap-2 p-2 border-2 border-blue-800 rounded-2xl dark:border-blue-200 bg-zinc-100 dark:bg-zinc-900 lg:-mb-4">

                    <flux:card class="flex flex-col h-full p-6  rounded-lg shadow-sm md:p-8 lg:pb-12">
                        <div class="space-y-6">

                            <div class="space-y-2">
                                <flux:badge icon="fire" size="sm" color="green" class="mb-2">Most popular
                                </flux:badge>
                                <flux:subheading class="!text-sm">Monthly Plan</flux:subheading>
                                <flux:heading class="!text-3xl">$4.99 / month</flux:heading>
                            </div>

                            <!-- Features -->
                            <div class="flex flex-col gap-2">

                                <div class="flex items-center gap-2">
                                    <flux:icon.fire class="text-amber-600 dark:text-amber-400" variant="solid" />
                                    <flux:heading>Unlimited tailored resumes</flux:heading>
                                </div>

                                <div class="flex items-center gap-2">
                                    <flux:icon.check-circle class="text-green-600 dark:text-green-400"
                                        variant="solid" />
                                    <flux:heading>ATS keyword optimization</flux:heading>
                                </div>

                                <div class="flex items-center gap-2">
                                    <flux:icon.check-circle class="text-green-600 dark:text-green-400"
                                        variant="solid" />
                                    <flux:heading>Grammar & clarity enhancements</flux:heading>
                                </div>

                                <div class="flex items-center gap-2">
                                    <flux:icon.check-circle class="text-green-600 dark:text-green-400"
                                        variant="solid" />
                                    <flux:heading>Professional formatting</flux:heading>
                                </div>

                                <div class="flex items-center gap-2">
                                    <flux:icon.check-circle class="text-green-600 dark:text-green-400"
                                        variant="solid" />
                                    <flux:heading>Cover letter optimization</flux:heading>
                                </div>

                                <div class="flex items-center gap-2">
                                    <flux:icon.check-circle class="text-green-600 dark:text-green-400"
                                        variant="solid" />
                                    <flux:heading>Priority delivery</flux:heading>
                                </div>
                            </div>

                            <flux:button class="w-full" as="link" href="{{ route('checkout', 1139017) }}"
                                variant="primary" icon-trailing="chevron-right">
                                Try for free
                            </flux:button>
                        </div>
                    </flux:card>
                </div>

                <!-- Yearly Plan -->
                <div
                    class="flex flex-col flex-1 gap-2 p-2 border rounded-2xl border-zinc-200 dark:border-zinc-700/75 bg-zinc-100 dark:bg-zinc-900 lg:mt-10 lg:rounded-l-none lg:border-l-0 lg:pl-0">

                    <flux:card class="flex flex-col h-full p-6  rounded-lg shadow-sm md:p-8 lg:rounded-l-none">
                        <div class="space-y-6">

                            <div class="space-y-2">
                                <flux:subheading class="!text-sm">Yearly Plan</flux:subheading>
                                <flux:heading class="!text-3xl">$39.99 / year</flux:heading>
                            </div>

                            <!-- Features -->
                            <div class="flex flex-col gap-2">

                                <div class="flex items-center gap-2">
                                    <flux:icon.fire class="text-amber-600 dark:text-amber-400" variant="solid" />
                                    <flux:heading>Unlimited tailored resumes</flux:heading>
                                </div>

                                <div class="flex items-center gap-2">
                                    <flux:icon.check-circle class="text-green-600 dark:text-green-400"
                                        variant="solid" />
                                    <flux:heading>ATS keyword optimization</flux:heading>
                                </div>

                                <div class="flex items-center gap-2">
                                    <flux:icon.check-circle class="text-green-600 dark:text-green-400"
                                        variant="solid" />
                                    <flux:heading>Grammar & clarity enhancements</flux:heading>
                                </div>

                                <div class="flex items-center gap-2">
                                    <flux:icon.check-circle class="text-green-600 dark:text-green-400"
                                        variant="solid" />
                                    <flux:heading>Professional formatting</flux:heading>
                                </div>

                                <div class="flex items-center gap-2">
                                    <flux:icon.check-circle class="text-green-600 dark:text-green-400"
                                        variant="solid" />
                                    <flux:heading>Cover letter optimization</flux:heading>
                                </div>

                                <div class="flex items-center gap-2">
                                    <flux:icon.check-circle class="text-green-600 dark:text-green-400"
                                        variant="solid" />
                                    <flux:heading>Priority delivery</flux:heading>
                                </div>

                            </div>
                            <flux:button class="w-full" as="link" href="{{ route('checkout', 1139078) }}"
                                variant="filled" icon-trailing="chevron-right">
                                Try for free
                            </flux:button>
                        </div>
                    </flux:card>
                </div>
            </div>

            <flux:separator variant="subtle" />

            <div class="flex items-center justify-center gap-2">
                <flux:subheading>Still not sure? Check out what our happy <flux:link class="!text-sm" wire:navigate
                        href="/customers">customers</flux:link> are saying.</flux:subheading>
            </div>
        </div>
    </section>
</x-layouts.main>
