<?php

namespace App\Livewire\Resume;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use App\Services\CvTailorService;
use Livewire\WithFileUploads;

class Demo extends Component
{
    use WithFileUploads;

    #[Validate('required|mimes:pdf,txt|max:10240')]
    public $resume = null;

    #[Validate('required|string|min:50')]
    public string $description = '';

    public string $candidateName = '';
    public string $tailored = '';
    public int $usageCount = 0;
    public string $cvText = '';

    public function mount()
    {
        $this->usageCount = session('cv_usage_count', 0);
    }

    public function startTailoring()
    {
        if ((!Auth::user() || !Auth::user()->isSubscribed()) && $this->usageCount >= 3) {
            $this->modal('limit-modal')->show();
            return;
        }

        $this->validate();

        session(['cv_usage_count' => $this->usageCount]);

        $this->dispatch('tailoring-demo-started');
    }

    /**
     * Step 2: tailor resume
     */
    public function tailorResumeDemo(CvTailorService $service)
    {
        $result = $service->tailorResume(
            resumePath: $this->resume->getRealPath(),
            jobDescription: $this->description
        );

        $this->tailored = $result['html'];
        $this->cvText = $result['cvText'];
        $this->candidateName = $result['name'];

        $this->modal('tailoring-demo-in-progress')->close();
        $this->usageCount++;
        $this->modal('tailoring-demo-result')->show();
    }


    /**
     * Download tailored resume as PDF
     */
    public function downloadPdf(CvTailorService $service)
    {
        $this->validate();

        return $service->downloadPdf(
            $this->tailored,
            $this->candidateName,
            $this->description
        );
    }

    public function render()
    {
        return view('livewire.resume.demo');
    }
}
