<?php

namespace App\Actions\Resume;

use App\Data\CoverLetterData;
use App\Data\JobPostingData;
use App\Data\ResumeData;
use Illuminate\Http\UploadedFile;

/**
 * Flujo de la carta de presentación: archivo + oferta → carta estructurada.
 *
 * El CV se parsea a datos antes de escribir la carta en vez de pasarle el
 * texto crudo al modelo. Así el nombre, el contacto y los hechos que la carta
 * cita salen de campos concretos y coinciden con el CV que se manda adjunto.
 */
class GenerateCoverLetter
{
    public function __construct(
        private readonly ExtractResumeText $extractText,
        private readonly ParseResume $parseResume,
        private readonly AnalyzeJobPosting $analyzeJob,
        private readonly WriteCoverLetter $writeCoverLetter,
    ) {}

    /**
     * @return array{letter: CoverLetterData, job: JobPostingData, resume: ResumeData}
     */
    public function handle(UploadedFile|string $file, string $jobDescription, ?string $company = null): array
    {
        $text = $this->extractText->handle($file);

        $job = $this->analyzeJob->handle($jobDescription);
        $resume = $this->parseResume->handle($text, $job->language);

        return [
            'letter' => $this->writeCoverLetter->handle($resume, $jobDescription, $job, $company),
            'job' => $job,
            'resume' => $resume,
        ];
    }
}
