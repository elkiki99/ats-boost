<x-layouts.main title="AI Resume Builder • Digizen">
    <section class="">
        <div class="space-y-6">
            <div class="!text-center lg:!text-start">
                <flux:badge color="blue" icon="sparkles" size="sm" variant="pill" class="mb-3">
                    AI-powered resume builder
                </flux:badge>
            </div>

            <flux:heading level="1"
                class="!text-5xl md:!text-6xl font-black max-w-4xl mx-auto lg:mx-0 lg:max-w-full text-center lg:text-start">
                Create a job-winning resume in minutes
                <br class="hidden lg:block">with <span
                    class="!text-5xl font-black text-transparent md:!text-6xl bg-gradient-to-r from-blue-600 via-blue-300 to-blue-500 bg-clip-text">
                    AI-powered precision
                </span>
            </flux:heading>

            <flux:subheading level="2"
                class="max-w-xl mx-auto text-sm text-center md:text-base lg:text-start lg:mx-0">
                Generate tailored resumes, optimize keywords, and boost your chances
                of landing interviews — all in seconds.
            </flux:subheading>

            <div class="flex items-center justify-center gap-4 lg:justify-start">
                <flux:button wire:navigate href="/dashboard" icon-trailing="arrow-up-right">
                    Try it free
                </flux:button>

                <flux:button variant="primary" wire:navigate href="/pricing" icon-trailing="arrow-up-right">
                    Upgrade now
                </flux:button>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section class="min-h-screen py-6 space-y-6">
        <flux:heading level="2" class="font-bold py-6 text-center !text-4xl lg:!text-5xl">
            Everything you need to stand out
        </flux:heading>

        <div class="grid grid-cols-1 gap-4 py-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3">

            <flux:card size="sm" class="!bg-transparent !p-8 hover:border-blue-50/50 transition duration-300">
                <flux:icon.fire class="mb-3 text-blue-600 dark:text-blue-400" variant="solid" />
                <flux:heading level="3">Unlimited tailored resumes</flux:heading>
                <flux:subheading>Generate as many resumes as you want, instantly personalized for each job.
                </flux:subheading>
            </flux:card>

            <flux:card size="sm" class="!bg-transparent !p-8 hover:border-blue-50/50 transition duration-300">
                <flux:icon.check-circle class="mb-3 text-green-600 dark:text-green-400" variant="solid" />
                <flux:heading level="3">ATS keyword optimization</flux:heading>
                <flux:subheading>Ensure your CV passes applicant tracking systems with AI-recommended keywords.
                </flux:subheading>
            </flux:card>

            <flux:card size="sm" class="!bg-transparent !p-8 hover:border-blue-50/50 transition duration-300">
                <flux:icon.check-circle class="mb-3 text-green-600 dark:text-green-400" variant="solid" />
                <flux:heading level="3">Grammar & clarity</flux:heading>
                <flux:subheading>Improve readability, tone and structure for maximum impact.</flux:subheading>
            </flux:card>

            <flux:card size="sm" class="!bg-transparent !p-8 hover:border-blue-50/50 transition duration-300">
                <flux:icon.check-circle class="mb-3 text-green-600 dark:text-green-400" variant="solid" />
                <flux:heading level="3">Professional formatting</flux:heading>
                <flux:subheading>Use clean, modern templates that hiring managers love.</flux:subheading>
            </flux:card>

            <flux:card size="sm" class="!bg-transparent !p-8 hover:border-blue-50/50 transition duration-300">
                <flux:icon.check-circle class="mb-3 text-green-600 dark:text-green-400" variant="solid" />
                <flux:heading level="3">Cover letter generator</flux:heading>
                <flux:subheading>Create tailored cover letters with one click.</flux:subheading>
            </flux:card>

            <flux:card size="sm" class="!bg-transparent !p-8 hover:border-blue-50/50 transition duration-300">
                <flux:icon.bolt class="mb-3 text-blue-600 dark:text-blue-400" variant="solid" />
                <flux:heading level="3">Priority delivery</flux:heading>
                <flux:subheading>Get resume versions generated instantly during peak hours.</flux:subheading>
            </flux:card>

        </div>

        <div class="flex justify-end">
            <flux:button as="link" variant="ghost" wire:navigate href="/pricing" icon-trailing="arrow-up-right">
                View pricing
            </flux:button>
        </div>
    </section>

    <!-- Case Studies (adaptado a tu producto) -->
    <section class="min-h-screen py-6 space-y-6">
        <flux:heading level="2" class="font-bold py-6 text-center !text-4xl lg:!text-5xl">
            Real people getting real results
        </flux:heading>

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
                        <flux:card>
                            <flux:text variant="strong" size="lg">
                                {{ $t['text'] }}
                            </flux:text>

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
    </section>

    <!-- FAQs -->
    <section class="min-h-screen py-6 space-y-6">
        <flux:heading level="2" class="font-bold text-center !text-4xl lg:!text-5xl py-6">
            Frequently asked questions
        </flux:heading>

        <flux:accordion exclusive transition class="w-full py-6 lg:col-span-2">
            <flux:accordion.item expanded>
                <flux:accordion.heading level="3">Is it compatible with ATS?</flux:accordion.heading>
                <flux:accordion.content>
                    Yes — every resume is optimized for modern ATS filters, keyword parsing, and clean formatting.
                </flux:accordion.content>
            </flux:accordion.item>

            <flux:accordion.item>
                <flux:accordion.heading level="3">How many resumes can I generate?</flux:accordion.heading>
                <flux:accordion.content>
                    Unlimited. Create as many tailored versions as you want for every job you apply to.
                </flux:accordion.content>
            </flux:accordion.item>

            <flux:accordion.item>
                <flux:accordion.heading level="3">Does it include cover letters?</flux:accordion.heading>
                <flux:accordion.content>
                    Yes — you can generate personalized, job-specific cover letters instantly.
                </flux:accordion.content>
            </flux:accordion.item>

            <flux:accordion.item>
                <flux:accordion.heading level="3">Can I export my resume?</flux:accordion.heading>
                <flux:accordion.content>
                    Absolutely. Download high-quality PDFs and store every version in your dashboard.
                </flux:accordion.content>
            </flux:accordion.item>

            <flux:accordion.item>
                <flux:accordion.heading level="3">Do you store my personal information?</flux:accordion.heading>
                <flux:accordion.content>
                    Your data stays private. Nothing is used for training or shared with third parties.
                </flux:accordion.content>
            </flux:accordion.item>

            <flux:accordion.item>
                <flux:accordion.heading level="3">Will the tool change or invent experience I don't have?
                    </flux:flux:accordion.heading>
                    <flux:accordion.content>
                        Never. It only reorganizes and rewrites your real experience to match the job description — no
                        fabrication.
                    </flux:accordion.content>
            </flux:accordion.item>

            <flux:accordion.item>
                <flux:accordion.heading level="3">Can I use it even if I’m not a developer?
                </flux:accordion.heading>
                <flux:accordion.content>
                    Yes — it's designed for all roles: tech, design, marketing, operations, HR, data, and more.
                </flux:accordion.content>
            </flux:accordion.item>

            <flux:accordion.item>
                <flux:accordion.heading level="3">Does it rewrite my entire resume?</flux:accordion.heading>
                <flux:accordion.content>
                    Only the parts that need improvement. It keeps your voice and structure while enhancing clarity and
                    relevance.
                </flux:accordion.content>
            </flux:accordion.item>

            <flux:accordion.item>
                <flux:accordion.heading level="3">What formats can I upload?</flux:accordion.heading>
                <flux:accordion.content>
                    PDF works best, but most text-based resumes can be parsed without issues.
                </flux:accordion.content>
            </flux:accordion.item>

            <flux:accordion.item>
                <flux:accordion.heading level="3">Does it work with international job applications?
                </flux:accordion.heading>
                <flux:accordion.content>
                    Yes — the tool adapts resumes for U.S., EU, UK, LATAM, and remote-first recruiters.
                </flux:accordion.content>
            </flux:accordion.item>

            <flux:accordion.item>
                <flux:accordion.heading level="3">Can I edit the resume after it's generated?
                </flux:accordion.heading>
                <flux:accordion.content>
                    Of course. You can tweak, rewrite, and regenerate as many times as you want.
                </flux:accordion.content>
            </flux:accordion.item>
        </flux:accordion>

        <div class="flex justify-end">
            <flux:button as="link" variant="ghost" wire:navigate href="/pricing" icon-trailing="arrow-up-right">
                Pricing
            </flux:button>
        </div>
    </section>
</x-layouts.main>
