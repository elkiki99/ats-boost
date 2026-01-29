<x-layouts.main title="Adapata tu curriculum • ATS Boost">
    <section class="space-y-6">
        <div class="!text-center lg:!text-start">
            <flux:badge color="blue" icon="sparkles" size="sm" variant="pill">
                Prueba nuestro demo
            </flux:badge>
        </div>

        <div>
            <flux:heading level="1"
                class="max-sm:hidden !text-5xl !mb-6 md:!text-6xl font-black max-w-4xl mx-auto lg:mx-0 lg:max-w-full text-center lg:text-start">
                <span>¿Mandás CVs y no te llaman?</span> Adaptalo<br>y
                conseguí el
                <span
                    class="text-5xl font-black text-transparent md:text-6xl dark:from-blue-500 dark:via-blue-300 dark:to-blue-600 bg-gradient-to-r from-blue-600 via-blue-400 to-blue-700 bg-clip-text">
                    trabajo que querés
                </span>
            </flux:heading>

            <!-- Mobile -->
            <flux:heading level="1" class="sm:hidden !text-5xl text-center">Adaptá tu CV y conseguí ese trabajo</flux:heading>

            <flux:subheading level="2"
                class="max-w-xl mx-auto text-sm text-center md:text-base lg:text-start lg:mx-0">
                Pega la oferta de trabajo, carga tu currículum y descarga un CV personalizado. <br>Nuestro motor
                remodela lo que hay
                en tu CV para que coincida mejor con el puesto.<br>
            </flux:subheading>
        </div>

        <livewire:resume.demo />

        <flux:subheading level="2" class="max-w-xl mx-auto text-sm text-center md:text-base lg:text-start lg:mx-0">
            La plantilla sigue un diseño refinado de estilo Harvard, creada para ayudarte a<br> verte
            pulido, profesional y memorable para los reclutadores.<br><br>

            Disfruta de múltiples descargas gratuitas — y desbloquea aún más con la versión premium.
        </flux:subheading>
    </section>
</x-layouts.main>
