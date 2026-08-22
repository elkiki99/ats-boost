<?php

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;

/**
 * Compila cada plantilla Blade y le corre el linter de PHP.
 *
 * Una directiva mal escrita (`@else` con una condición entre paréntesis, por
 * ejemplo) no rompe nada al compilar: produce PHP válido que se comporta
 * distinto de lo que dice el código. Este test no atrapa eso, pero sí atrapa
 * la familia de errores que sí revientan en tiempo de ejecución, y en una
 * vista que solo se ve tras pagar una suscripción eso llega tarde.
 */
it('compila todas las plantillas Blade a PHP válido', function (): void {
    $views = collect(File::allFiles(resource_path('views')))
        ->filter(fn ($file): bool => str_ends_with($file->getFilename(), '.blade.php'));

    expect($views)->not->toBeEmpty();

    $broken = [];

    foreach ($views as $view) {
        $compiled = tempnam(sys_get_temp_dir(), 'blade').'.php';

        file_put_contents($compiled, Blade::compileString($view->getContents()));

        exec(escapeshellarg(PHP_BINARY).' -l '.escapeshellarg($compiled).' 2>&1', $output, $status);

        @unlink($compiled);

        if ($status !== 0) {
            $broken[$view->getRelativePathname()] = implode("\n", $output);
        }

        $output = [];
    }

    expect($broken)->toBe([]);
});
