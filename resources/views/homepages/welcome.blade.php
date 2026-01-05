<x-layouts.main title="Tailor your resume • ATS Boost">
    <section class="space-y-6">
        <div class="!text-center lg:!text-start">
            <flux:badge color="blue" icon="sparkles" size="sm" variant="pill">
                Try our demo
            </flux:badge>
        </div>

        <div>
            <flux:heading level="1"
                class="!text-5xl !mb-6 md:!text-6xl font-black max-w-4xl mx-auto lg:mx-0 lg:max-w-full text-center lg:text-start">
                <span class="max-sm:hidden">Struggling to get hired?</span> Tailor your<br class="hidden lg:block">
                resume and
                <span
                    class="text-5xl font-black text-transparent md:text-6xl dark:from-blue-500 dark:via-blue-300 dark:to-blue-600 bg-gradient-to-r from-blue-600 via-blue-400 to-blue-700 bg-clip-text">
                    land your dream job
                </span>
            </flux:heading>

            <flux:subheading level="2"
                class="max-w-xl mx-auto text-sm text-center md:text-base lg:text-start lg:mx-0">
                Paste the job offer, upload your resume, and download a tailored CV. Our <br>engine reshapes what’s
                in your resume to better match the role.<br>
            </flux:subheading>
        </div>

        <livewire:resume.demo />

        {{-- <video width="320" height="240" autoplay muted>
            <source src="demo.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video> --}}

        <flux:subheading level="2" class="max-w-xl mx-auto text-sm text-center md:text-base lg:text-start lg:mx-0">
            The template follows a refined Harvard-style layout, crafted to help you<br> look
            polished, professional, and memorable to recruiters.<br><br>

            Enjoy multiple free downloads — and unlock even more with premium.
        </flux:subheading>
    </section>
</x-layouts.main>
