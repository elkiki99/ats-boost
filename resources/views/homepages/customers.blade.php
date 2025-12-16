<x-layouts.main title="Customers • ATS Boost">
    <section class="space-y-6">
        <div class="text-center">
            <flux:badge color="blue" icon="megaphone" size="sm" variant="pill">
                They speak for ourselves
            </flux:badge>
        </div>

        <flux:heading level="1"
            class="!text-5xl !mb-6 md:!text-6xl font-black max-w-4xl mx-auto lg:max-w-full text-center">
            See why professionals trust us<br class="hidden lg:block">
            to elevate <span
                class="text-5xl font-black text-transparent md:text-6xl dark:from-blue-500 dark:via-blue-300 dark:to-blue-600 bg-gradient-to-r from-blue-600 via-blue-400 to-blue-700 bg-clip-text">
                their resumes</span>
        </flux:heading>

        <flux:subheading level="2" class="max-w-xl mx-auto text-sm text-center md:text-base">
            Real users. Real results. Discover how ATS Boost improves clarity, impact, and job-winning performance in
            every resume.
        </flux:subheading>

        <div class="flex items-center justify-center gap-4">
            <flux:button wire:navigate href="/dashboard" icon-trailing="arrow-up-right">
                Try it free
            </flux:button>

            <flux:button variant="primary" wire:navigate href="/pricing" icon-trailing="arrow-up-right">
                Upgrade now
            </flux:button>
        </div>

        <div class="flex flex-col py-6 space-y-6">
            <div class="columns-1 sm:columns-2 lg:columns-3 gap-6 space-y-6 py-6">
                @foreach ([
        [
            'name' => 'Emily Carter',
            'role' => 'Full-Stack Developer',
            'img' => 'emilycarter',
            'text' => 'I used to spend hours rewriting my CV for every job. This tool adapts everything in minutes while keeping my real experience intact. It genuinely boosted my interview rate.',
        ],
        [
            'name' => 'Jason Lee',
            'role' => 'Junior Software Engineer',
            'img' => 'jasonlee',
            'text' => 'I had no idea how to highlight what companies actually cared about. The tailored version did it for me—clean, relevant, and more confident without exaggeration.',
        ],
        [
            'name' => 'Hannah Smith',
            'role' => 'Customer Support Specialist transitioning to Tech',
            'img' => 'hannahsmith',
            'text' => 'I was switching careers and my CV felt chaotic. The tailored version aligned everything to the job description so well that I finally started getting callbacks.',
        ],
        [
            'name' => 'Michael Brown',
            'role' => 'Frontend Developer',
            'img' => 'michaelbrown',
            'text' => 'It reorganized my CV in a way I had never managed on my own. Same experience, better presentation. I landed two interviews in a week.',
        ],
        [
            'name' => 'Samantha Reyes',
            'role' => 'Data Analyst',
            'img' => 'samanthareyes',
            'text' => 'My CV had good content but lacked structure. The tailored version made it crisp, relevant, and easy to scan. Recruiters started replying almost immediately.',
        ],
        [
            'name' => 'Daniel Kim',
            'role' => 'Mobile Developer',
            'img' => 'danielkim',
            'text' => 'It doesn’t invent anything—just makes your actual experience match what the company needs. Simple idea, huge impact on results.',
        ],
        [
            'name' => 'Olivia Thompson',
            'role' => 'UX Designer',
            'img' => 'oliviathompson',
            'text' => "I love that it respects my tone. My CV finally reads like me, but focused and aligned with the role. It opened doors I couldn't reach before.",
        ],
        [
            'name' => 'Ryan Mitchell',
            'role' => 'Entry-Level IT Technician',
            'img' => 'ryanmitchell',
            'text' => 'I struggled to explain my skills clearly. The tailored CV made everything easy to understand and role-focused. I got my first tech interview thanks to it.',
        ],
        [
            'name' => 'Chloe Davis',
            'role' => 'Backend Developer',
            'img' => 'chloedavis',
            'text' => 'The tool is incredibly precise. My CV now highlights exactly what hiring managers want to see. Nothing fake—just my experience, but optimized.',
        ],
    ] as $t)
                    <div class="break-inside-avoid">
                        <flux:card size="sm"
                            class="relative overflow-hidden
        p-6
        backdrop-blur-sm
        transition-all duration-300


        hover:-translate-y-[2px]

        hover:shadow-md dark:hover:shadow-none
        hover:shadow-blue-500/5


        rounded-xl">

                            <blockquote class="italic font-medium">
                                {{-- <p> --}}
                                <flux:text variant="strong" size="lg">
                                    "{{ $t['text'] }}"
                                </flux:text>
                                {{-- </p> --}}
                            </blockquote>

                            <div class="mt-6 flex items-center gap-4">
                                <flux:avatar size="lg" src="https://unavatar.io/x/{{ $t['img'] }}" />
                                <div>
                                    <flux:heading size="lg">{{ $t['name'] }}</flux:heading>
                                    <flux:text>{{ $t['role'] }}</flux:text>
                                </div>
                            </div>
                        </flux:card>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="flex justify-end">
            <flux:button as="link" variant="ghost" wire:navigate href="/features" icon-trailing="arrow-up-right">
                View features
            </flux:button>
        </div>
    </section>
</x-layouts.main>
