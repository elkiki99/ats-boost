<?php

namespace App\Livewire\Resume;

use App\Services\AnalyzeResumeService;
use Flux\Flux;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class ResumeAnalyzer extends Component
{
    use WithFileUploads;

    #[Validate('required|mimes:pdf,txt|max:10240', as: 'curriculum')]
    public $resume;

    public int $score = 0;

    public string $improved = '';

    public string $candidateName = '';

    /**
     * STEP 1 — Click "Analyze my CV"
     */
    public function startAnalyzing()
    {
        $this->validate();
        $this->dispatch('analysis-started');
    }

    /**
     * STEP 2 — Analyze resume
     */
    public function analyzeResume(AnalyzeResumeService $service)
    {
        $this->score = $service->analyzeResume($this->resume);
        $this->dispatch('analysis-finished');
    }

    /**
     * STEP 3 — Click "Improve my resume"
     */
    public function startImproving()
    {
        $this->dispatch('improving-started');
    }

    /**
     * STEP 4 — Improve resume
     */
    public function improveResume(AnalyzeResumeService $service)
    {
        $result = $service->improveResume($this->resume);

        $this->improved = $result['html'];
        $this->candidateName = $result['name'] ?? 'Unknown';

        $this->dispatch('improving-finished');
    }

    /**
     * STEP 5 — Download PDF
     */
    public function downloadPdf(AnalyzeResumeService $service)
    {
        Flux::toast(
            heading: 'PDF ready!',
            text: 'Your improved resume was downloaded successfully.',
            variant: 'success',
        );

        return $service->downloadPdf(
            $this->improved,
            $this->candidateName
        );
    }

    public function render()
    {
        return view('livewire.resume.resume-analyzer')
            ->title('Analizador de curriculum • ATS Boost');
    }
}
