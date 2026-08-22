<?php

namespace Database\Factories;

use App\Enums\DocumentType;
use App\Enums\Language;
use App\Enums\ResumeTemplate;
use App\Models\Document;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Document>
 */
class DocumentFactory extends Factory
{
    protected $model = Document::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'type' => DocumentType::TailoredResume,
            'title' => $this->faker->jobTitle(),
            'language' => Language::Spanish,
            'template' => ResumeTemplate::Modern,
            'payload' => self::resumePayload(),
            'report' => null,
            'role' => $this->faker->jobTitle(),
            'company' => $this->faker->company(),
            'job_description' => $this->faker->paragraph(6),
            'source_filename' => 'cv.pdf',
            'ats_score' => null,
        ];
    }

    public function coverLetter(): static
    {
        return $this->state(fn (): array => [
            'type' => DocumentType::CoverLetter,
            'payload' => self::letterPayload(),
        ]);
    }

    public function improved(): static
    {
        return $this->state(fn (): array => [
            'type' => DocumentType::ImprovedResume,
            'ats_score' => $this->faker->numberBetween(60, 95),
        ]);
    }

    /**
     * Currículum de ejemplo con todas las secciones pobladas. Sirve para
     * comprobar que la plantilla del PDF las renderiza todas.
     *
     * @return array<string, mixed>
     */
    public static function resumePayload(): array
    {
        return [
            'full_name' => 'Bruno Rossani',
            'headline' => 'Desarrollador de Software — PHP · Laravel · Vue.js',
            'contact' => [
                'location' => 'Montevideo, Uruguay',
                'email' => 'brossani23@gmail.com',
                'phone' => '+598 91 845 585',
                'links' => ['github.com/elkiki99', 'linkedin.com/in/brunorossani'],
            ],
            'summary' => null,
            'experience' => [
                [
                    'role' => 'Desarrollador de Software',
                    'company' => 'Multiline Contact Center',
                    'location' => 'Montevideo, Uruguay',
                    'dates' => 'May 2026 – Presente',
                    'bullets' => [
                        'Desarrollo funcionalidades en PHP y Laravel para los sistemas internos del contact center',
                        'Mantengo más de 12 aplicaciones críticas para 5 clientes corporativos',
                        'Actualizo código legacy de Laravel 8 a 13 para mantener las aplicaciones soportadas',
                    ],
                ],
            ],
            'education' => [
                [
                    'degree' => 'Tecnólogo Informático',
                    'institution' => 'Universidad Tecnológica del Uruguay (UTEC)',
                    'location' => 'Campus Buceo, Montevideo',
                    'dates' => '2024 – 2027',
                    'description' => 'Ingeniería de Software, Estructuras de Datos y Algoritmos, Bases de Datos',
                ],
            ],
            'projects' => [
                [
                    'name' => 'ATS Boost',
                    'description' => 'Aplicación web con integración de LLM que adapta un CV a cualquier oferta',
                    'meta' => '2026',
                    'link' => 'ats-boost.com',
                ],
            ],
            'skills' => [
                ['label' => 'Programación', 'value' => 'PHP, C/C++, SQL, Java, JavaScript'],
                ['label' => 'Frameworks', 'value' => 'Laravel, Livewire, Vue.js, Tailwind CSS'],
                ['label' => 'Bases de datos', 'value' => 'MySQL, PostgreSQL, SQLite, Redis'],
            ],
            'certifications' => [
                ['name' => "CS50's Introduction to Computer Science", 'issuer' => 'Harvard Online', 'year' => null],
            ],
            'language' => 'es',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function letterPayload(): array
    {
        return [
            'candidate_name' => 'Bruno Rossani',
            'contact' => [
                'location' => 'Montevideo, Uruguay',
                'email' => 'brossani23@gmail.com',
                'phone' => '+598 91 845 585',
                'links' => [],
            ],
            'role' => 'Desarrollador de Software',
            'company' => 'Multiline Contact Center',
            'greeting' => 'Estimado equipo de Multiline Contact Center:',
            'paragraphs' => [
                'Les escribo para postularme a la búsqueda de Desarrollador de Software.',
                'En mi puesto actual mantengo más de 12 aplicaciones Laravel en producción.',
                'Quedo a disposición para conversar sobre la posición.',
            ],
            'closing' => 'Saludos cordiales,',
            'language' => 'es',
        ];
    }
}
