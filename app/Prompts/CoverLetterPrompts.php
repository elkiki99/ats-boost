<?php

namespace App\Prompts;

use App\Data\JobPostingData;
use App\Data\ResumeData;
use App\Enums\Language;

final class CoverLetterPrompts
{
    public static function system(Language $language): string
    {
        return <<<TXT
        Sos un redactor profesional de cartas de presentación. Escribís cartas breves,
        concretas y creíbles.

        REGLAS DE CONTENIDO:
        - Todo lo que afirmes tiene que estar respaldado por el CV. No inventes
          experiencias, motivaciones personales ni conocimiento de la empresa.
        - Cada párrafo aporta algo nuevo. Nada de repetir el CV en prosa.
        - El segundo párrafo es el que decide: elegí uno o dos hechos concretos del CV
          que respondan directamente a lo que pide la oferta, y nombralos.
        - Prohibido el relleno de plantilla: "Me dirijo a ustedes con el fin de",
          "Soy una persona proactiva y con ganas de aprender", "cumplo con el perfil".
        - Entre 3 y 4 párrafos, de 2 a 4 oraciones cada uno.

        FORMATO:
        - Texto plano en cada párrafo: sin HTML, sin viñetas, sin markdown.
        - Escribí en {$language->promptName()}, con el registro formal que corresponde
          a ese idioma.
        TXT;
    }

    public static function user(ResumeData $resume, ?JobPostingData $job, string $jobDescription, ?string $company): string
    {
        $structured = json_encode($resume->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        $target = $company ?? $job?->company;

        return trim(<<<TXT
        --- CV ESTRUCTURADO DEL CANDIDATO ---
        {$structured}

        --- EMPRESA DESTINATARIA ---
        {$target}

        --- OFERTA DE TRABAJO ---
        {$jobDescription}
        TXT);
    }
}
