<?php

use App\Actions\Resume\BuildDocumentFileName;
use App\Actions\Resume\RenderCoverLetterPdf;
use App\Actions\Resume\RenderResumePdf;
use App\Data\CoverLetterData;
use App\Data\ResumeData;
use App\Enums\DocumentType;
use App\Enums\ResumeTemplate;
use Database\Factories\DocumentFactory;
use Smalot\PdfParser\Parser;

/**
 * Estos tests compilan el PDF de verdad y le vuelven a extraer el texto.
 *
 * Es la única forma de comprobar que la plantilla no perdió una sección por
 * el camino: DomPDF no falla cuando un bloque no se renderiza, simplemente lo
 * omite del documento final.
 */
function pdfText(string $binary): string
{
    $path = tempnam(sys_get_temp_dir(), 'ats').'.pdf';
    file_put_contents($path, $binary);

    try {
        return (new Parser)->parseFile($path)->getText();
    } finally {
        @unlink($path);
    }
}

it('renderiza todas las secciones del currículum', function (): void {
    $resume = ResumeData::from(DocumentFactory::resumePayload());

    $text = pdfText(app(RenderResumePdf::class)->handle($resume));

    expect($text)
        ->toContain('Bruno Rossani')
        ->toContain('Desarrollador de Software')
        ->toContain('Multiline Contact Center')
        ->toContain('Montevideo, Uruguay')
        // Encabezados de sección traducidos al idioma del CV.
        ->toContain('Experiencia')
        ->toContain('Educación')
        ->toContain('Proyectos')
        ->toContain('Habilidades')
        ->toContain('Certificaciones')
        // Fila de la tabla de habilidades: etiqueta y valores.
        ->toContain('Frameworks')
        ->toContain('Laravel')
        ->toContain('ATS Boost');
});

it('mantiene el rango de fechas junto a cada puesto', function (): void {
    $resume = ResumeData::from(DocumentFactory::resumePayload());

    $text = pdfText(app(RenderResumePdf::class)->handle($resume));

    // El guion largo y los acentos son la prueba de que la fuente incrustada
    // cubre Unicode: con Helvetica salen como cuadrados o desaparecen.
    expect($text)->toContain('May 2026 – Presente');
});

it('rinde el currículum en inglés con los encabezados en inglés', function (): void {
    $payload = DocumentFactory::resumePayload();
    $payload['language'] = 'en';

    $text = pdfText(app(RenderResumePdf::class)->handle(ResumeData::from($payload)));

    expect($text)
        ->toContain('Experience')
        ->toContain('Skills')
        ->not->toContain('Habilidades');
});

it('renderiza ambas plantillas', function (ResumeTemplate $template): void {
    $resume = ResumeData::from(DocumentFactory::resumePayload());

    $binary = app(RenderResumePdf::class)->handle($resume, $template);

    expect($binary)->toStartWith('%PDF-');
    expect(pdfText($binary))->toContain('Bruno Rossani');
})->with([
    'moderna' => ResumeTemplate::Modern,
    'compacta' => ResumeTemplate::Compact,
]);

it('renderiza la carta de presentación con encabezado y firma', function (): void {
    $letter = CoverLetterData::from(DocumentFactory::letterPayload());

    $text = pdfText(app(RenderCoverLetterPdf::class)->handle($letter));

    expect($text)
        ->toContain('Bruno Rossani')
        ->toContain('Estimado equipo de Multiline Contact Center')
        ->toContain('Saludos cordiales');
});

it('usa marcadores de posición cuando el CV no traía nombre ni contacto', function (): void {
    $payload = DocumentFactory::resumePayload();
    $payload['full_name'] = '';
    $payload['contact'] = ['location' => null, 'email' => null, 'phone' => null, 'links' => []];

    $text = pdfText(app(RenderResumePdf::class)->handle(ResumeData::from($payload)));

    expect($text)
        ->toContain('Insertar nombre aquí')
        ->toContain('Insertar ciudad');
});

it('arma el nombre del archivo con candidato, puesto y empresa', function (): void {
    $name = app(BuildDocumentFileName::class)->handle(
        DocumentType::TailoredResume,
        'Bruno Rossani',
        'Desarrollador de Software',
        'Multiline',
    );

    expect($name)->toBe('Bruno Rossani - Desarrollador de Software - Multiline.pdf');
});

it('limpia del nombre del archivo los caracteres que rompen el sistema de archivos', function (): void {
    $name = app(BuildDocumentFileName::class)->handle(
        DocumentType::CoverLetter,
        'Ana/Paula\\Díaz',
        'Dev: Backend?',
    );

    expect($name)
        ->not->toContain('/')
        ->not->toContain('\\')
        ->not->toContain(':')
        ->not->toContain('?')
        ->toEndWith('.pdf');
});

it('cae en la etiqueta del tipo cuando no se conoce el nombre del candidato', function (): void {
    $name = app(BuildDocumentFileName::class)->handle(DocumentType::CoverLetter, null);

    expect($name)->toBe('Carta de presentación.pdf');
});
