<?php

namespace App\Actions\Resume;

use App\Data\AtsReportData;
use App\Data\ResumeData;

/**
 * Paso 2 del analizador: del texto ya extraído a la versión corregida.
 *
 * Recibe el texto que dejó GenerateResumeAnalysis, así que mejorar un CV
 * recién analizado no vuelve a abrir el archivo ni a puntuarlo.
 */
class GenerateImprovedResume
{
    public function __construct(
        private readonly ParseResume $parseResume,
        private readonly ImproveResume $improveResume,
        private readonly ScoreResume $scoreResume,
    ) {}

    /**
     * @return array{resume: ResumeData, report: AtsReportData}
     */
    public function handle(string $resumeText): array
    {
        $resume = $this->improveResume->handle(
            $this->parseResume->handle($resumeText)
        );

        // Se vuelve a puntuar sobre el resultado para poder mostrarle al
        // usuario cuánto subió. Sin el "antes y después" el número inicial no
        // le sirve para nada.
        return [
            'resume' => $resume,
            'report' => $this->scoreResume->handle($resume->toPlainText()),
        ];
    }
}
