<x-layouts.main title="Características • ATS Boost">
    <section class="space-y-6">
        <div class="!text-center lg:!text-start">
            <flux:badge color="blue" icon="cpu-chip" size="sm" variant="pill">
                Constructor de currículum basado en IA
            </flux:badge>
        </div>

        <flux:heading level="1"
            class="!text-5xl !mb-6 md:!text-6xl font-black max-w-4xl mx-auto lg:mx-0 lg:max-w-full text-center lg:text-start hidden md:block">
            Hecho para darte una ventaja injusta
            <br class="hidden lg:block">en el<span
                class="!text-5xl font-black text-transparent md:!text-6xl bg-gradient-to-r from-blue-600 via-blue-300 to-blue-500 bg-clip-text">
                mundo laboral
            </span>
        </flux:heading>

        <!-- Mobile -->
        <flux:heading level="1" class="md:hidden !text-5xl text-center">Hecho para darte ventaja
        </flux:heading>

        <flux:subheading level="2" class="max-w-xl mx-auto text-sm text-center md:text-base lg:text-start lg:mx-0">
            Genera currículos personalizados, optimiza palabras clave y aumenta tus posibilidades
            de conseguir entrevistas — todo en segundos.
        </flux:subheading>

        <div class="flex items-center justify-center gap-4 lg:justify-start">
            <flux:button wire:navigate href="{{ route('dashboard') }}" icon-trailing="arrow-up-right">
                Prueba gratis
            </flux:button>

            <flux:button variant="primary" wire:navigate href="{{ route('pricing') }}" icon-trailing="arrow-up-right">
                Actualizar ahora
            </flux:button>
        </div>
    </section>

    <!-- Features -->
    <section class="py-12 space-y-6">
        <div class="grid grid-cols-1 gap-4 py-6 sm:grid-cols-2 md:grid-cols-3">
            <x-feature-card icon="fire" color="text-orange-600 dark:text-orange-400"
                title="Currículos ilimitados personalizados">
                Genera tantos currículos como quieras, personalizados al instante para cada trabajo.
            </x-feature-card>

            <x-feature-card icon="sparkles" color="text-green-600 dark:text-green-400"
                title="Optimización de palabras clave ATS">
                Asegura que tu CV pase los sistemas de seguimiento de candidatos con palabras clave recomendadas por IA.
            </x-feature-card>

            <x-feature-card icon="language" color="text-indigo-600 dark:text-indigo-400" title="Gramática y claridad">
                Mejora la legibilidad, el tono y la estructura para máximo impacto.
            </x-feature-card>

            <x-feature-card icon="document-text" color="text-sky-600 dark:text-sky-400" title="Formato profesional">
                Usa plantillas limpias y modernas que los reclutadores aman.
            </x-feature-card>

            <x-feature-card icon="pencil-square" color="text-purple-600 dark:text-purple-400"
                title="Generador de carta de presentación">
                Crea cartas de presentación personalizadas con un solo clic.
            </x-feature-card>

            <x-feature-card icon="bolt" color="text-yellow-600 dark:text-yellow-400" title="Entrega prioritaria">
                Obtén versiones de currículum generadas al instante durante horas pico.
            </x-feature-card>
        </div>
    </section>

    <!-- FAQs -->
    <section class="py-6 space-y-6 max-w-4xl mx-auto">
        <div>
            <div class="!text-center">
                <flux:badge color="blue" icon="sparkles" size="sm" variant="pill">
                    ¿Alguna pregunta?
                </flux:badge>
            </div>

            <flux:heading level="2" class="font-bold text-center !text-4xl lg:!text-5xl py-6">
                Preguntas frecuentes
            </flux:heading>
        </div>

        <flux:accordion variant="reverse" exclusive transition class="w-full py-6 lg:col-span-2">
            <flux:accordion.item expanded>
                <flux:accordion.heading level="3">¿Es compatible con ATS?</flux:accordion.heading>
                <flux:accordion.content>
                    Sí — cada currículum está optimizado para filtros ATS modernos, análisis de palabras clave y formato
                    limpio.
                </flux:accordion.content>
            </flux:accordion.item>

            <flux:accordion.item>
                <flux:accordion.heading level="3">¿Cuántos currículos puedo generar?</flux:accordion.heading>
                <flux:accordion.content>
                    Ilimitado. Crea tantas versiones personalizadas como quieras para cada trabajo al que te postules.
                </flux:accordion.content>
            </flux:accordion.item>

            <flux:accordion.item>
                <flux:accordion.heading level="3">¿Incluye cartas de presentación?</flux:accordion.heading>
                <flux:accordion.content>
                    Sí — puedes generar cartas de presentación personalizadas y específicas para cada trabajo al
                    instante.
                </flux:accordion.content>
            </flux:accordion.item>

            <flux:accordion.item>
                <flux:accordion.heading level="3">¿Puedo exportar mi currículum?</flux:accordion.heading>
                <flux:accordion.content>
                    Absolutamente. Descarga PDF de alta calidad y guarda cada versión en tu panel.
                </flux:accordion.content>
            </flux:accordion.item>

            <flux:accordion.item>
                <flux:accordion.heading level="3">¿Guardan mi información personal?</flux:accordion.heading>
                <flux:accordion.content>
                    Tus datos permanecen privados. Nada se utiliza para entrenar o se comparte con terceros.
                </flux:accordion.content>
            </flux:accordion.item>

            <flux:accordion.item>
                <flux:accordion.heading level="3">¿La herramienta cambiará o inventará experiencia que no tengo?
                </flux:accordion.heading>
                <flux:accordion.content>
                    Nunca. Solo reorganiza y rescribe tu experiencia real para que coincida con la descripción del
                    trabajo — sin fabricación.
                </flux:accordion.content>
            </flux:accordion.item>

            <flux:accordion.item>
                <flux:accordion.heading level="3">¿Puedo usarlo aunque no sea desarrollador?
                </flux:accordion.heading>
                <flux:accordion.content>
                    Sí — está diseñado para todos los roles: tecnología, diseño, marketing, operaciones, RRHH, datos y
                    más.
                </flux:accordion.content>
            </flux:accordion.item>

            <flux:accordion.item>
                <flux:accordion.heading level="3">¿Reescribe todo mi currículum?</flux:accordion.heading>
                <flux:accordion.content>
                    Solo las partes que necesitan mejora. Mantiene tu voz y estructura mientras mejora la claridad y
                    relevancia.
                </flux:accordion.content>
            </flux:accordion.item>

            <flux:accordion.item>
                <flux:accordion.heading level="3">¿Qué formatos puedo cargar?</flux:accordion.heading>
                <flux:accordion.content>
                    Actualmente solo aceptamos archivos PDF, con soporte para más formatos próximamente.
                </flux:accordion.content>
            </flux:accordion.item>

            <flux:accordion.item>
                <flux:accordion.heading level="3">¿Funciona con solicitudes de empleo internacionales?
                </flux:accordion.heading>
                <flux:accordion.content>
                    Sí — la herramienta adapta currículum para reclutadores de EE.UU., UE, Reino Unido, LATAM y remotos.
                </flux:accordion.content>
            </flux:accordion.item>

            <flux:accordion.item>
                <flux:accordion.heading level="3">¿Puedo editar el currículum después de que se genere?
                </flux:accordion.heading>
                <flux:accordion.content>
                    Por supuesto. Puedes ajustar, reescribir y regenerar tantas veces como quieras.
                </flux:accordion.content>
            </flux:accordion.item>

            <flux:accordion.item>
                <flux:accordion.heading level="3">
                    ¿Por qué veo un cargo de MERCADO PAGO?
                </flux:accordion.heading>
                <flux:accordion.content>
                    Esto es completamente normal — <strong>MERCADO PAGO</strong> es nuestro proveedor de
                    pagos seguro que usamos para suscripciones y facturación.
                    Es un servicio confiable que protege tus pagos y datos financieros.
                </flux:accordion.content>
            </flux:accordion.item>
        </flux:accordion>

        <div class="flex justify-end">
            <flux:button as="link" variant="ghost" wire:navigate href="{{ route('pricing') }}" icon-trailing="arrow-up-right">
                Precios
            </flux:button>
        </div>
    </section>
</x-layouts.main>
