<?php

use App\Data\ResumeData;
use App\Enums\Language;

/**
 * El modelo devuelve JSON que valida contra el esquema pero igual trae
 * sorpresas: cadenas "null", listas donde se esperaba texto, entradas vacías.
 * ResumeData es el filtro que impide que eso llegue a la plantilla del PDF.
 */
it('descarta las entradas que el modelo devolvió vacías', function (): void {
    $resume = ResumeData::from([
        'full_name' => 'Ana Díaz',
        'experience' => [
            ['role' => 'Desarrolladora', 'company' => 'Acme'],
            ['role' => '', 'company' => ''],
            ['role' => '', 'company' => null, 'bullets' => ['algo']],
        ],
    ]);

    expect($resume->experience)->toHaveCount(1)
        ->and($resume->experience[0]->role)->toBe('Desarrolladora');
});

it('trata como ausentes los literales que el modelo usa para "no sé"', function (): void {
    $resume = ResumeData::from([
        'full_name' => 'Ana Díaz',
        'headline' => 'null',
        'contact' => ['location' => 'N/A', 'email' => '', 'phone' => 'Desconocido', 'links' => []],
    ]);

    expect($resume->headline)->toBeNull()
        ->and($resume->contact->location)->toBeNull()
        ->and($resume->contact->email)->toBeNull()
        ->and($resume->contact->phone)->toBeNull()
        ->and($resume->contact->isEmpty())->toBeTrue();
});

it('aplana la lista cuando el modelo manda habilidades como array', function (): void {
    $resume = ResumeData::from([
        'full_name' => 'Ana Díaz',
        'skills' => [
            ['label' => 'Frameworks', 'items' => ['Laravel', 'Livewire', 'Vue.js']],
            ['category' => 'Bases de datos', 'value' => 'MySQL, Redis'],
        ],
    ]);

    expect($resume->skills[0]->value)->toBe('Laravel, Livewire, Vue.js')
        ->and($resume->skills[1]->label)->toBe('Bases de datos');
});

it('reindexa las listas para que sigan siendo listas JSON', function (): void {
    $resume = ResumeData::from([
        'full_name' => 'Ana Díaz',
        'experience' => [
            ['role' => 'Dev', 'bullets' => [0 => 'uno', 2 => 'tres', 5 => null]],
        ],
    ]);

    $bullets = $resume->toArray()['experience'][0]['bullets'];

    // Sin reindexar, json_encode emitiría {"0":"uno","2":"tres"} y el foreach
    // de la plantilla del PDF recibiría claves salteadas.
    expect(array_is_list($bullets))->toBeTrue()
        ->and($bullets)->toBe(['uno', 'tres']);
});

it('sobrevive a un payload completamente vacío', function (): void {
    $resume = ResumeData::from([]);

    expect($resume->fullName)->toBe('')
        ->and($resume->isEmpty())->toBeTrue()
        ->and($resume->displayName())->toBe('Insertar nombre aquí')
        ->and($resume->contact->line())->toBe('');
});

it('arma la línea de contacto sin separadores huérfanos', function (): void {
    $resume = ResumeData::from([
        'full_name' => 'Ana Díaz',
        'contact' => ['location' => 'Montevideo', 'email' => null, 'phone' => '+598 99', 'links' => ['github.com/ana']],
    ]);

    expect($resume->contact->line())->toBe('Montevideo · +598 99 · github.com/ana');
});

it('sobrevive al viaje de ida y vuelta a array', function (): void {
    $original = ResumeData::from(Database\Factories\DocumentFactory::resumePayload());

    expect(ResumeData::from($original->toArray())->toArray())->toBe($original->toArray());
});

it('respeta el idioma que se le pasa por encima del que trae el payload', function (): void {
    $resume = ResumeData::from(['full_name' => 'Ana', 'language' => 'es'], Language::English);

    expect($resume->language)->toBe(Language::English)
        ->and($resume->displayName())->toBe('Ana');
});

it('convierte el currículum a texto plano para volver a puntuarlo', function (): void {
    $text = ResumeData::from(Database\Factories\DocumentFactory::resumePayload())->toPlainText();

    expect($text)
        ->toContain('Bruno Rossani')
        ->toContain('Desarrollador de Software, Multiline Contact Center')
        ->toContain('- Mantengo más de 12 aplicaciones')
        ->toContain('Frameworks: Laravel');
});
