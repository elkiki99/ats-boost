<?php

namespace App\Livewire\Resume;

use App\Services\CvTailorService;
use Flux\Flux;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class ResumeTailor extends Component
{
    use WithFileUploads;

    #[Validate('required|mimes:pdf,txt|max:10240', as: 'curriculum')]
    public $resume = null;

    #[Validate('required|string|min:50')]
    public string $description = '';

    public string $candidateName = '';

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
        $result = $service->tailorResume(
            resumePath: $this->resume->getRealPath(),
            jobDescription: $this->description
        );

        $this->tailored = $result['html'];
        $this->cvText = $result['cvText'];
        $this->candidateName = $result['name'];

        $this->modal('analyzing-in-progress')->close();
        $this->modal('tailoring-result')->show();
    }

    /**
     * Download tailored resume as PDF
     */
    public function downloadPdf(CvTailorService $service)
    {
        $this->validate();

        Flux::toast(
            heading: '¡Pdf listo!',
            text: 'Tu currículum adaptado se descargó correctamente.',
            variant: 'success',
        );

        return $service->downloadPdf(
            $this->tailored,
            $this->candidateName,
            $this->description
        );
    }

    public function render()
    {
        return view('livewire.resume.resume-tailor')
            ->title('Adaptador de curriculum • ATS Boost');
    }
}
