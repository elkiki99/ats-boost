<?php

namespace App\Livewire\Resume;

use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\WithFileUploads;
use App\Services\CvTailorService;

class ResumeTailor extends Component
{
    use WithFileUploads;

    #[Validate('required|mimes:pdf,txt|max:10240')]
    public $resume = null;

    #[Validate('required|string|min:50')]
    public string $description = '';

    public string $tailored = '';
    public string $cvText = '';


    /**
     * Step 1: validate + open progress modal
     */
    public function startTailoring()
    {
        $this->validate();
        $this->dispatch('tailoring-started');
    }

    /**
     * Step 2: tailor resume
     */
    public function tailorResume(CvTailorService $service)
    {
        $this->tailored = $service->tailorResume(
            resumePath: $this->resume->getRealPath(),
            jobDescription: $this->description
        );

        $this->modal('tailoring-in-progress')->close();
        $this->modal('tailoring-result')->show();
    }

    /**
     * Download tailored resume as PDF
     */
    public function downloadPdf(CvTailorService $service)
    {
        $this->validate();

        return $service->downloadPdf(
            $this->tailored,
            $this->cvText,
            $this->description
        );
    }


    public function render()
    {
        return view('livewire.resume.resume-tailor')
            ->title('Resume Tailor • ATS Boost');
    }
}
