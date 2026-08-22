<?php

namespace App\Actions\Resume;

use App\Data\AtsReportData;
use Illuminate\Http\UploadedFile;

/**
 * Flujo del analizador: archivo → puntaje ATS con desglose.
 *
 * Devuelve también el texto extraído para que el paso siguiente (mejorar el
 * CV) no tenga que volver a abrir el PDF.
 */
class GenerateResumeAnalysis
{
    public function __construct(
        private readonly ExtractResumeText $extractText,
        private readonly ScoreResume $scoreResume,
    ) {}

    /**
     * @return array{report: AtsReportData, text: string}
     */
    public function handle(UploadedFile|string $file): array
    {
        $text = $this->extractText->handle($file);

        return [
            'report' => $this->scoreResume->handle($text),
            'text' => $text,
        ];
    }
}
