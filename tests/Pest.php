<?php

use App\Models\Subscriber;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use OpenAI\Laravel\Facades\OpenAI;
use OpenAI\Responses\Chat\CreateResponse;

pest()->extend(Tests\TestCase::class)
    ->use(Illuminate\Foundation\Testing\RefreshDatabase::class)
    ->in('Feature');

/*
|--------------------------------------------------------------------------
| Helpers del dominio
|--------------------------------------------------------------------------
*/

/**
 * Un usuario con suscripción vigente.
 */
function subscribedUser(array $attributes = []): User
{
    $user = User::factory()->create($attributes);

    Subscriber::factory()->for($user)->create();

    return $user->refresh();
}

/**
 * Encola respuestas del modelo, en orden.
 *
 * Cada payload se serializa como JSON en el contenido del mensaje, que es
 * exactamente lo que devuelve la API cuando se pide salida estructurada.
 *
 * @param  array<string, mixed>  ...$payloads
 */
function fakeChatResponses(array ...$payloads): void
{
    OpenAI::fake(array_map(
        fn (array $payload): CreateResponse => CreateResponse::fake([
            'choices' => [
                ['message' => ['content' => json_encode($payload, JSON_UNESCAPED_UNICODE)]],
            ],
        ]),
        $payloads,
    ));
}

/**
 * Un CV de prueba con texto suficiente para pasar el umbral de extracción.
 */
function fakeResumeUpload(string $name = 'cv.txt'): UploadedFile
{
    return UploadedFile::fake()->createWithContent($name, <<<'TXT'
    Bruno Rossani
    Desarrollador de Software
    Montevideo, Uruguay · brossani23@gmail.com · +598 91 845 585

    Experiencia
    Desarrollador de Software, Multiline Contact Center, May 2026 - Presente
    Desarrollo funcionalidades en PHP y Laravel para los sistemas internos.
    Mantengo mas de 12 aplicaciones criticas para 5 clientes corporativos.

    Educacion
    Tecnologo Informatico, Universidad Tecnologica del Uruguay, 2024 - 2027

    Habilidades
    PHP, Laravel, Livewire, Vue.js, MySQL, PostgreSQL, Docker, Git
    TXT);
}

/**
 * Una oferta lo bastante larga para pasar la validación de 80 caracteres.
 */
function fakeJobDescription(): string
{
    return 'Buscamos un desarrollador backend con experiencia en PHP y Laravel para sumarse a nuestro '
        .'equipo de producto. Vas a trabajar con Livewire, MySQL y Docker, participando del ciclo '
        .'completo de desarrollo, desde el analisis de requerimientos hasta el despliegue.';
}

/**
 * Lectura de oferta que devolvería AnalyzeJobPosting.
 *
 * @return array<string, mixed>
 */
function fakeJobPosting(): array
{
    return [
        'role' => 'Desarrollador Backend',
        'company' => 'Acme',
        'seniority' => 'semi senior',
        'keywords' => ['PHP', 'Laravel', 'Livewire', 'MySQL', 'Docker'],
        'requirements' => ['Experiencia con PHP y Laravel'],
        'responsibilities' => ['Desarrollar funcionalidades de producto'],
    ];
}
