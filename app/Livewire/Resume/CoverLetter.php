<?php

namespace App\Livewire\Resume;

use App\Services\CoverLetterService;
use Livewire\Attributes\Validate;
use Livewire\WithFileUploads;
use Livewire\Component;

class CoverLetter extends Component
{
    use WithFileUploads;

    #[Validate('required|mimes:pdf,txt|max:10240')]
    public $resume = null;

    #[Validate('nullable|string|min:3')]
    public ?string $company = null;

    #[Validate('required|string|min:50')]
    public string $description = '';

    public string $coverLetter = '';

    /**
     * Step 1: validate + open progress modal
     */
    public function startGeneratingCoverLetter()
    {
        $this->validate();

        $this->dispatch('cover-letter-started');
    }

    /**
     * Step 2: generate cover letter
     */
    public function coverLetterResume(CoverLetterService $service)
    {
        // Extract CV text (mandatory)
        $cvText = $service->extractCvText(
            $this->resume->getRealPath()
        );

        // Generate cover letter (name + role inferred internally)
        $this->coverLetter = $service->generateCoverLetter(
            description: $this->description,
            cvText: $cvText,
            company: $this->company
        );

        $this->modal('cover-letter-in-progress')->close();
        $this->modal('cover-letter-result')->show();
    }

    /**
     * Download as PDF
     */
    public function downloadPdf(CoverLetterService $service)
    {
        $pdfContent = $service->generatePdf($this->coverLetter);

        // Infer profile again only for filename (cheap + safe)
        $cvText = $service->extractCvText(
            $this->resume->getRealPath()
        );

        $profile = $service->inferCandidateProfile($cvText);
        
        $fileName = $service->buildFileName(
            name: $profile['name'] ?? null,
            role: $profile['role'] ?? null,
            company: $this->company
        );

        return response()->streamDownload(
            fn () => print($pdfContent),
            $fileName
        );
    }

    public function render()
    {
        return view('livewire.resume.cover-letter')
            ->title('Cover Letter • ATS Boost');
    }
}