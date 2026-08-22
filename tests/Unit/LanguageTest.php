<?php

use App\Enums\Language;

/**
 * La detección se resolvió localmente para ahorrar una llamada al modelo por
 * generación. Estos casos son el precio de esa decisión: si el heurístico se
 * rompe, el CV sale en el idioma equivocado.
 */
it('detecta el español', function (): void {
    $text = 'Buscamos un desarrollador con experiencia en PHP para el equipo de producto. '
        .'El candidato ideal tiene conocimientos de bases de datos y trabaja con metodologías ágiles.';

    expect(Language::detect($text))->toBe(Language::Spanish);
});

it('detecta el inglés', function (): void {
    $text = 'We are looking for a backend developer with experience in PHP and Laravel. '
        .'The ideal candidate has knowledge of databases and is comfortable with agile methodologies.';

    expect(Language::detect($text))->toBe(Language::English);
});

it('cae en el idioma por defecto cuando el texto no decide nada', function (): void {
    expect(Language::detect('PHP Laravel Docker MySQL Git'))->toBe(Language::English)
        ->and(Language::detect('PHP Laravel Docker', Language::Spanish))->toBe(Language::Spanish);
});

it('no se confunde con nombres de tecnología en inglés dentro de un texto español', function (): void {
    $text = 'Desarrollo aplicaciones con Laravel y Vue.js para el equipo de producto de la empresa, '
        .'y trabajo con bases de datos MySQL y con Docker en los entornos de desarrollo.';

    expect(Language::detect($text))->toBe(Language::Spanish);
});

it('elige el valor correcto según el idioma', function (): void {
    expect(Language::Spanish->pick('sí', 'yes'))->toBe('sí')
        ->and(Language::English->pick('sí', 'yes'))->toBe('yes')
        ->and(Language::Spanish->isSpanish())->toBeTrue();
});
