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
                    Struggling to get hired? Boost your<br> ATS score by tailoring your CV to<br> finally <span
                        class="bg-gradient-to-l from-blue-500 via-blue-600 to-blue-800 bg-clip-text text-transparent">land
                        your dream job</span>
                </flux:heading>

                <flux:subheading class="text-lg!">
                    Just paste the job offer you want to apply to, upload your resume, and download your <br>tailored
                    CV! Our engine simply reshapes what's already in your resume to better <br>match the role,
                    changing as little as possible while maximizing relevance.<br><br>
                </flux:subheading>
            </div>

            <livewire:ats-input />

            <flux:subheading class="text-lg!">

                The template is based on a curated Harvard-style layout, ensuring<br>
                you look professional and stand out to recruiters.<br><br>

                And the best part? It's completely free — no credit card needed.
            </flux:subheading>

            {{-- <div class="columns-1 sm:columns-2 lg:columns-3 gap-6 space-y-6 py-6">
                @foreach ([
                    [
                        'name' => 'Laura Gómez',
                        'role' => 'Recruiter en Globant',
                        'img' => 'lauragomez',
                        'text' => 'Subí un CV desordenado y recibí una versión perfectamente alineada a la oferta. Ahorra tiempo y mejora la presentación sin inventar nada.',
                    ],
                    [
                        'name' => 'Miguel Torres',
                        'role' => 'Desarrollador Full-Stack',
                        'img' => 'migueltorres',
                        'text' => 'Siempre me costaba adaptar mi CV a cada oferta. Esta app lo hace en segundos manteniendo mi voz y mi experiencia real.',
                    ],
                    [
                        'name' => 'Sofía Pereira',
                        'role' => 'Asistente de Recursos Humanos',
                        'img' => 'sofiapereira',
                        'text' => 'Revisar CVs adaptados así es un placer. Se nota quién usa esta herramienta: la información es clara y va directo a lo que pedimos.',
                    ],
                    [
                        'name' => 'Daniel Rojas',
                        'role' => 'Ingeniero QA',
                        'img' => 'danielrojas',
                        'text' => 'Me ayudó a destacar exactamente lo que pedía la empresa sin modificar mi experiencia. Pasé de ignorado a preseleccionado.',
                    ],
                    [
                        'name' => 'Carla Müller',
                        'role' => 'Especialista en Talent Acquisition',
                        'img' => 'carlamuller',
                        'text' => 'Los CV adaptados con esta app llegan mucho más claros. Destaca lo relevante para cada oferta sin agregar cosas falsas.',
                    ],
                    [
                        'name' => 'Juan Herrera',
                        'role' => 'Analista de Datos',
                        'img' => 'juanherrera',
                        'text' => 'Nunca supe cómo traducir mis tareas al lenguaje de la oferta. La app reordena, reescribe y aclara sin cambiar la historia.',
                    ],
                    [
                        'name' => 'Mariana Silva',
                        'role' => 'Diseñadora UX',
                        'img' => 'marianasilva',
                        'text' => 'Me encantó que respeta mi tono. Solo reorganiza y adapta para que coincida con lo que buscan. Literalmente me abrió puertas.',
                    ],
                    [
                        'name' => 'Roberto Álvarez',
                        'role' => 'Practicante Front-End',
                        'img' => 'robertoalvarez',
                        'text' => 'Conseguí mi primera entrevista técnica usando un CV adaptado acá. Pequeños cambios que hicieron una gran diferencia.',
                    ],
                    [
                        'name' => 'Elena Fernández',
                        'role' => 'Ingeniera de Software',
                        'img' => 'elenafernandez',
                        'text' => 'La herramienta es increíblemente precisa. Mi CV ahora refleja exactamente lo que la empresa busca, sin exagerar nada.',
                    ],
                ] as $t)
                    <div class="break-inside-avoid">
                        <flux:card>
                            <flux:text size="lg">
                                <blockquote>{{ $t['text'] }}</blockquote>
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
            </div> --}}
        </div>
    </flux:main>

    @fluxScripts
</body>

</html>
