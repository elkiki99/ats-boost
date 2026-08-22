<?php

namespace App\Actions\Resume;

use App\Data\JobPostingData;
use App\Data\ResumeData;
use Illuminate\Http\UploadedFile;

/**
 * Flujo completo de adaptación: archivo + oferta → currículum adaptado.
 *
 * Son tres llamadas al modelo (leer el CV, leer la oferta, adaptar), contra
 * las siete del motor anterior — que además repetía dos de ellas al momento
 * de descargar el PDF. Las tres son independientes, así que readaptar el
 * mismo CV a otra oferta reutiliza el parseo y cuesta dos.
 */
class GenerateTailoredResume
{
    public function __construct(
        private readonly ExtractResumeText $extractText,
        private readonly ParseResume $parseResume,
        private readonly AnalyzeJobPosting $analyzeJob,
        private readonly TailorResume $tailorResume,
    ) {}

    /**
     * @return array{resume: ResumeData, job: JobPostingData, source: ResumeData, text: string}
     */
    public function handle(UploadedFile|string $file, string $jobDescription): array
    {
        $text = $this->extractText->handle($file);

        // La oferta se lee primero porque define el idioma de salida: un CV en
        // español que se postula a una búsqueda en inglés se adapta en inglés.
        $job = $this->analyzeJob->handle($jobDescription);

        $source = $this->parseResume->handle($text, $job->language);

        return [
            'resume' => $this->tailorResume->handle($source, $job, $jobDescription),
            'job' => $job,
            'source' => $source,
            'text' => $text,
        ];
    }

    /**
     * Readapta un CV ya parseado a otra oferta, sin volver a leer el archivo.
     *
     * @return array{resume: ResumeData, job: JobPostingData}
     */
    public function again(ResumeData $source, string $jobDescription): array
    {
        $job = $this->analyzeJob->handle($jobDescription);

        return [
            'resume' => $this->tailorResume->handle($source, $job, $jobDescription),
            'job' => $job,
        ];
    }
}
