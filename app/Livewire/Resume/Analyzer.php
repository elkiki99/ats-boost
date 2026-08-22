<?php

namespace App\Livewire\Resume;

use App\Actions\Documents\StoreDocument;
use App\Actions\Resume\GenerateImprovedResume;
use App\Actions\Resume\GenerateResumeAnalysis;
use App\Data\AtsReportData;
use App\Enums\DocumentType;
use App\Livewire\Concerns\HandlesGenerationFailures;
use App\Livewire\Forms\AnalyzeResumeForm;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\Features\SupportRedirects\Redirector;
use Livewire\WithFileUploads;

class Analyzer extends Component
{
    use HandlesGenerationFailures, WithFileUploads;

    public AnalyzeResumeForm $form;

    /**
     * Informe del CV subido, tal como está hoy.
     *
     * @var array<string, mixed>|null
     */
    public ?array $report = null;

    /**
     * Texto ya extraído del archivo. Se conserva entre peticiones para que
     * "mejorar" no vuelva a abrir el PDF ni dependa de que el archivo
     * temporal de Livewire siga existiendo.
     */
    #[Locked]
    public string $resumeText = '';

    #[Locked]
    public ?string $sourceFilename = null;

    public function analyze(GenerateResumeAnalysis $analyze): void
    {
        $this->form->validate();

        $this->reset('report', 'resumeText');

        $result = $this->attempt(fn () => $analyze->handle($this->form->resume));

        if ($result === null) {
            return;
        }

        $this->report = $result['report']->toArray();
        $this->resumeText = $result['text'];
        $this->sourceFilename = $this->form->resume->getClientOriginalName();
    }

    public function improve(GenerateImprovedResume $improve, StoreDocument $store): ?Redirector
    {
        if ($this->resumeText === '') {
            $this->failed('Analizá un currículum antes de mejorarlo.');

            return null;
        }

        $result = $this->attempt(fn () => $improve->handle($this->resumeText));

        if ($result === null) {
            return null;
        }

        $document = $store->handle(
            user: auth()->user(),
            type: DocumentType::ImprovedResume,
            data: $result['resume'],
            report: $result['report'],
            sourceFilename: $this->sourceFilename,
        );

        $this->succeeded(
            'Currículum mejorado',
            "Tu puntaje pasó de {$this->currentScore()} a {$result['report']->score}.",
        );

        return $this->redirectRoute('documents.edit', $document, navigate: true);
    }

    public function atsReport(): ?AtsReportData
    {
        return $this->report === null ? null : AtsReportData::from($this->report);
    }

    private function currentScore(): int
    {
        return $this->atsReport()?->score ?? 0;
    }

    public function render()
    {
        return view('livewire.resume.analyzer', ['ats' => $this->atsReport()])
            ->title('Analizar currículum • ATS Boost');
    }
}
