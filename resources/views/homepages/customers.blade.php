<x-layouts.main title="Clientes • ATS Boost">
    <section class="space-y-6">
        <div class="text-center">
            <flux:badge color="blue" icon="megaphone" size="sm" variant="pill">
                Hablan por sí solos
            </flux:badge>
        </div>

        <flux:heading level="1"
            class="!text-5xl !mb-6 md:!text-6xl font-black max-w-4xl mx-auto lg:max-w-full text-center hidden md:block">
            Ve por qué los profesionales nos confían<br class="hidden lg:block">
            para mejorar <span
                class="text-5xl font-black text-transparent md:text-6xl dark:from-blue-500 dark:via-blue-300 dark:to-blue-600 bg-gradient-to-r from-blue-600 via-blue-400 to-blue-700 bg-clip-text">
                sus currículum</span>
        </flux:heading>

        <!-- Mobile -->
        <flux:heading level="1" class="md:hidden !text-5xl text-center">Ve por qué nos confían</flux:heading>

        <flux:subheading level="2" class="max-w-xl mx-auto text-sm text-center md:text-base">
            Usuarios reales. Resultados reales. Descubre cómo ATS Boost mejora la claridad, el impacto y el desempeño
            ganador de empleos en cada currículum.
        </flux:subheading>

        <div class="flex items-center justify-center gap-4">
            <flux:button wire:navigate href="{{ route('dashboard') }}" icon-trailing="arrow-up-right">
                Prueba gratis
            </flux:button>

            <flux:button variant="primary" wire:navigate href="{{ route('pricing') }}" icon-trailing="arrow-up-right">
                Actualizar ahora
            </flux:button>
        </div>

        <div class="flex flex-col py-6 space-y-6">
            <div class="columns-1 sm:columns-2 lg:columns-3 gap-6 space-y-6 py-6">
                @foreach ([
        [
            'name' => 'Emily Carter',
            'role' => 'Desarrolladora Full-Stack',
            'img' => 'emilycarter',
            'text' => 'Solía pasar horas rescribiendo mi CV para cada trabajo. Esta herramienta adapta todo en minutos manteniendo mi experiencia real intacta. Genuinamente aumenté mi tasa de entrevistas.',
        ],
        [
            'name' => 'Jason Lee',
            'role' => 'Ingeniero de Software Junior',
            'img' => 'jasonlee',
            'text' => 'No sabía cómo destacar lo que las empresas realmente valoraban. La versión personalizada lo hizo por mí — limpia, relevante y más confiada sin exageración.',
        ],
        [
            'name' => 'Hannah Smith',
            'role' => 'Especialista en Atención al Cliente en transición a Tecnología',
            'img' => 'hannahsmith',
            'text' => 'Estaba cambiando de carrera y mi CV se sentiá caótico. La versión personalizada alineó todo perfectamente con la descripción del trabajo, así que finalmente empecé a recibir respuestas.',
        ],
        [
            'name' => 'Michael Brown',
            'role' => 'Desarrollador Frontend',
            'img' => 'michaelbrown',
            'text' => 'Reorganizó mi CV de una manera que nunca había logrado por mi cuenta. Misma experiencia, mejor presentación. Conseguí dos entrevistas en una semana.',
        ],
        [
            'name' => 'Samantha Reyes',
            'role' => 'Analista de Datos',
            'img' => 'samanthareyes',
            'text' => 'Mi CV tenía buen contenido pero carecía de estructura. La versión personalizada lo hizo crujiente, relevante y fácil de escanear. Los reclutadores comenzaron a responder casi de inmediato.',
        ],
        [
            'name' => 'Daniel Kim',
            'role' => 'Desarrollador Móvil',
            'img' => 'danielkim',
            'text' => 'No inventa nada — solo hace que tu experiencia real coincida con lo que la empresa necesita. Idea simple, impacto enorme en los resultados.',
        ],
        [
            'name' => 'Olivia Thompson',
            'role' => 'Diseñadora UX',
            'img' => 'oliviathompson',
            'text' => 'Me encanta que respete mi tono. Mi CV finalmente se lee como yo, pero enfocado y alineado con el rol. Abrió puertas que no podía alcanzar antes.',
        ],
        [
            'name' => 'Ryan Mitchell',
            'role' => 'Técnico en Tecnología de la Información de Entrada',
            'img' => 'ryanmitchell',
            'text' => 'Luchaba por explicar mis habilidades claramente. El CV personalizado hizo que todo fuera fácil de entender y enfocado en el rol. Conseguí mi primera entrevista de tecnología gracias a él.',
        ],
        [
            'name' => 'Chloe Davis',
            'role' => 'Desarrolladora Backend',
            'img' => 'chloedavis',
            'text' => 'La herramienta es increíblemente precisa. Mi CV ahora destaca exactamente lo que los gerentes de contratación quieren ver. Nada falso — solo mi experiencia, pero optimizada.',
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
            <flux:button as="link" variant="ghost" wire:navigate href="{{ route('features') }}" icon-trailing="arrow-up-right">
                Ver características
            </flux:button>
        </div>
    </section>
</x-layouts.main>
