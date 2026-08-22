<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Modelos de OpenAI
    |--------------------------------------------------------------------------
    |
    | Cada tarea usa el modelo más barato que la resuelve bien. La extracción
    | estructurada y el scoring son tareas mecánicas; la adaptación y la carta
    | de presentación requieren redacción, por eso usan el modelo grande.
    |
    */

    'models' => [
        'parsing' => env('RESUME_MODEL_PARSING', 'gpt-4.1-mini'),
        'tailoring' => env('RESUME_MODEL_TAILORING', 'gpt-4.1'),
        'analysis' => env('RESUME_MODEL_ANALYSIS', 'gpt-4.1-mini'),
        'cover_letter' => env('RESUME_MODEL_COVER_LETTER', 'gpt-4.1'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Límites de entrada
    |--------------------------------------------------------------------------
    */

    'limits' => [
        'upload_kilobytes' => 10240,
        'cv_characters' => 24000,
        'job_description_characters' => 12000,
        'bullets_per_entry' => 5,
        'demo_generations' => 2,
    ],

    /*
    |--------------------------------------------------------------------------
    | Reintentos ante respuestas inválidas del modelo
    |--------------------------------------------------------------------------
    */

    'retries' => env('RESUME_AI_RETRIES', 2),

    /*
    |--------------------------------------------------------------------------
    | Plantillas de PDF
    |--------------------------------------------------------------------------
    |
    | DejaVu Sans es la única familia con cobertura Unicode completa que
    | DomPDF trae de fábrica: sin ella los guiones largos, las viñetas y los
    | acentos salen como cuadrados. Para usar otra fuente hay que instalarla
    | con `php artisan dompdf:install-fonts` o dejar el .ttf en storage/fonts.
    |
    */

    'pdf' => [
        'font' => env('RESUME_PDF_FONT', 'DejaVu Sans'),
        'paper' => env('RESUME_PDF_PAPER', 'a4'),
    ],

];
