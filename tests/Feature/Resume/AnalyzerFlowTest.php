<?php

use App\Enums\DocumentType;
use App\Livewire\Resume\Analyzer;
use App\Models\Document;
use Database\Factories\DocumentFactory;
use Livewire\Livewire;

beforeEach(function (): void {
    $this->user = subscribedUser();
});

/**
 * @return array<string, mixed>
 */
function fakeAtsReport(int $score = 64): array
{
    return [
        'score' => $score,
        'breakdown' => ['structure' => 70, 'parsability' => 80, 'content' => 55, 'keywords' => 50],
        'strengths' => ['Tus fechas son consistentes en todo el documento'],
        'issues' => [
            ['title' => 'Viñetas sin resultado medible', 'detail' => 'Agregá una cifra donde la tengas.', 'severity' => 'high'],
            ['title' => 'Faltan verbos de acción', 'detail' => 'Empezá cada viñeta con un verbo.', 'severity' => 'medium'],
        ],
        'missing_keywords' => ['Docker', 'CI/CD'],
    ];
}

it('muestra el puntaje con su desglose y los problemas concretos', function (): void {
    fakeChatResponses(fakeAtsReport());

    Livewire::actingAs($this->user)
        ->test(Analyzer::class)
        ->set('form.resume', fakeResumeUpload())
        ->call('analyze')
        ->assertHasNoErrors()
        ->assertSet('report.score', 64)
        ->assertSee('Viñetas sin resultado medible')
        ->assertSee('Docker');
});

it('guarda el texto extraído para no volver a abrir el archivo al mejorar', function (): void {
    fakeChatResponses(fakeAtsReport());

    $component = Livewire::actingAs($this->user)
        ->test(Analyzer::class)
        ->set('form.resume', fakeResumeUpload())
        ->call('analyze');

    expect($component->get('resumeText'))->toContain('Multiline Contact Center');
});

it('mejora el currículum y guarda el documento con el puntaje nuevo', function (): void {
    fakeChatResponses(
        fakeAtsReport(58),                  // análisis inicial
        DocumentFactory::resumePayload(),   // parseo
        DocumentFactory::resumePayload(),   // mejora
        fakeAtsReport(88),                  // repuntaje
    );

    Livewire::actingAs($this->user)
        ->test(Analyzer::class)
        ->set('form.resume', fakeResumeUpload())
        ->call('analyze')
        ->call('improve')
        ->assertRedirect();

    $document = Document::sole();

    expect($document->type)->toBe(DocumentType::ImprovedResume)
        ->and($document->ats_score)->toBe(88)
        ->and($document->atsReport()?->issues)->toHaveCount(2);
});

it('no deja mejorar antes de analizar', function (): void {
    Livewire::actingAs($this->user)
        ->test(Analyzer::class)
        ->call('improve')
        ->assertNoRedirect();

    expect(Document::count())->toBe(0);
});

it('acota el puntaje que devuelve el modelo al rango válido', function (): void {
    $report = fakeAtsReport();
    $report['score'] = 140;

    fakeChatResponses($report);

    Livewire::actingAs($this->user)
        ->test(Analyzer::class)
        ->set('form.resume', fakeResumeUpload())
        ->call('analyze')
        ->assertSet('report.score', 100);
});
