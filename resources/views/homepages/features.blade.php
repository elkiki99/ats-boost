<x-layouts.main title="Features • ATS Boost">
    <section class="space-y-6">
        <div class="!text-center lg:!text-start">
            <flux:badge color="blue" icon="sparkles" size="sm" variant="pill">
                AI-powered resume builder
            </flux:badge>
        </div>

        <flux:heading level="1"
            class="!text-5xl md:!text-6xl font-black max-w-4xl mx-auto lg:mx-0 lg:max-w-full text-center lg:text-start">
            Built to give you an unfair advantage
            <br class="hidden lg:block">in the<span
                class="!text-5xl font-black text-transparent md:!text-6xl bg-gradient-to-r from-blue-600 via-blue-300 to-blue-500 bg-clip-text">
                job market
            </span>
        </flux:heading>

        <flux:subheading level="2" class="max-w-xl mx-auto text-sm text-center md:text-base lg:text-start lg:mx-0">
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
    </section>

    <!-- Features -->
    <section class="py-12 space-y-6">
        <div class="grid grid-cols-1 gap-4 py-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3">
            <flux:card size="sm"
                class="
        relative overflow-hidden
        backdrop-blur-sm
        transition-all duration-300

        hover:-translate-y-[2px]

        hover:shadow-md dark:hover:shadow-none
        hover:shadow-blue-500/5

        rounded-xl
    ">
                <!-- Soft background glow -->
                <div class="absolute inset-0 pointer-events-none opacity-0 hover:opacity-100 transition duration-500">
                    <div
                        class="absolute -inset-10 bg-gradient-to-br from-blue-500/10 via-blue-300/5 to-blue-600/10 blur-2xl">
                    </div>
                </div>

                <flux:icon.fire class="mb-3 text-blue-600 dark:text-blue-400" variant="solid" />

                <flux:heading level="3">Unlimited tailored resumes</flux:heading>

                <flux:subheading>
                    Generate as many resumes as you want, instantly personalized for each job.
                </flux:subheading>
            </flux:card>

            <flux:card size="sm"
                class="
        relative overflow-hidden
        backdrop-blur-sm
        transition-all duration-300

        hover:-translate-y-[2px]

        hover:shadow-md dark:hover:shadow-none
        hover:shadow-blue-500/5

        rounded-xl
    ">
                <flux:icon.check-circle class="mb-3 text-green-600 dark:text-green-400" variant="solid" />
                <flux:heading level="3">ATS keyword optimization</flux:heading>
                <flux:subheading>Ensure your CV passes applicant tracking systems with AI-recommended keywords.
                </flux:subheading>
            </flux:card>

            <flux:card size="sm"
                class="
        relative overflow-hidden
        backdrop-blur-sm
        transition-all duration-300

        hover:-translate-y-[2px]

        hover:shadow-md dark:hover:shadow-none
        hover:shadow-blue-500/5

        rounded-xl
    ">
                <flux:icon.check-circle class="mb-3 text-green-600 dark:text-green-400" variant="solid" />
                <flux:heading level="3">Grammar & clarity</flux:heading>
                <flux:subheading>Improve readability, tone and structure for maximum impact.</flux:subheading>
            </flux:card>

            <flux:card size="sm"
                class="
        relative overflow-hidden
        backdrop-blur-sm
        transition-all duration-300

        hover:-translate-y-[2px]

        hover:shadow-md dark:hover:shadow-none
        hover:shadow-blue-500/5

        rounded-xl
    ">
                <flux:icon.check-circle class="mb-3 text-green-600 dark:text-green-400" variant="solid" />
                <flux:heading level="3">Professional formatting</flux:heading>
                <flux:subheading>Use clean, modern templates that hiring managers love.</flux:subheading>
            </flux:card>

            <flux:card size="sm"
                class="
        relative overflow-hidden
        backdrop-blur-sm
        transition-all duration-300

        hover:-translate-y-[2px]

        hover:shadow-md dark:hover:shadow-none
        hover:shadow-blue-500/5

        rounded-xl
    ">
                <flux:icon.check-circle class="mb-3 text-green-600 dark:text-green-400" variant="solid" />
                <flux:heading level="3">Cover letter generator</flux:heading>
                <flux:subheading>Create tailored cover letters with one click.</flux:subheading>
            </flux:card>

            <flux:card size="sm"
                class="
        relative overflow-hidden
        backdrop-blur-sm
        transition-all duration-300

        hover:-translate-y-[2px]

        hover:shadow-md dark:hover:shadow-none
        hover:shadow-blue-500/5

        rounded-xl
    ">
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

    <!-- FAQs -->
    <section class="py-6 space-y-6">
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

            <flux:accordion.item>
                <flux:accordion.heading level="3">
                    Why do I see a charge from LEMSQZY* STORE?
                </flux:accordion.heading>
                <flux:accordion.content>
                    This is completely normal — <strong>LEMSQZY* STORE</strong> is Lemon Squeezy, the secure payment
                    provider we use for subscriptions and billing.
                    You can read more in their official explanation here:
                    <a href="https://www.lemonsqueezy.com/why-did-lemon-squeeezy-charge-me" target="_blank"
                        class="text-blue-600 underline hover:text-blue-700">
                        Why am I seeing this charge?
                    </a>
                </flux:accordion.content>
            </flux:accordion.item>
        </flux:accordion>



        {{-- <a href="https://www.lemonsqueezy.com/why-did-lemon-squeeezy-charge-me">Lemon Squeezy Charge<a> --}}


        <div class="flex justify-end">
            <flux:button as="link" variant="ghost" wire:navigate href="/customers" icon-trailing="arrow-up-right">
                Customers
            </flux:button>
        </div>
    </section>
</x-layouts.main>
