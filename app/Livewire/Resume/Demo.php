<?php

namespace App\Livewire\Resume;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use App\Services\CvTailorService;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\WithFileUploads;
use Smalot\PdfParser\Parser;

class Demo extends Component
{
    use WithFileUploads;

    #[Validate('required|mimes:pdf,txt|max:10240')]
    public $resume = null;

    #[Validate('required|string|min:50')]
    public $description = '';

    public int $usageCount = 0;

    public $tailored = '';
    public $role = '';
    public $candidateName = '';

    public function mount()
    {
        $this->usageCount = session('cv_usage_count', 0);
    }

    public function uploadResume($resume)
    {
        $this->resume = $resume;
    }

    public function startTailoring()
    {
        if ((!Auth::user() || !Auth::user()->isSubscribed()) && $this->usageCount >= 3) {
            $this->modal('limit-modal')->show();
            return;
        }

        $this->validate();

        $this->usageCount++;
        session(['cv_usage_count' => $this->usageCount]);

        $this->dispatch('tailoring-demo-started');
    }

    public function tailorResumeDemo()
    {
        $path = $this->resume->getRealPath();
        $cvText = $this->extractPdf($path);

        if (!$cvText) {
            $this->addError('resume', 'We were not able to read your PDF.');
            return;
        }

        $service = app(CvTailorService::class);

        $name = app(CvTailorService::class)->extractNameFromCv($cvText);
        $this->candidateName = $name;

        $role = $service->extractRole($this->description);
        $this->role = $role;

        $requirements = $service->extractRequirements($this->description);

        $this->tailored = $service->tailor(
            $cvText,
            $this->description,
            $requirements
        );

        $this->modal('tailoring-demo-in-progress')->close();

        $this->modal('tailoring-demo-result')->show();
    }

    public function downloadPdf()
    {
        $html = $this->tailored;

        $pdf = Pdf::loadHTML("
            <html>
                <head>
                    <style>
                        body {
                            font-family: 'Calibri', sans-serif;
                            font-size: 12px; /* normal text */
                            font-weight: normal;
                            line-height: 1.4;
                            margin: 30px;
                        }

                        h1 {
                            font-family: 'Calibri', sans-serif;
                            font-size: 14px; /* Calibri bold 14 */
                            font-weight: bold;
                            margin-bottom: 4px;
                            text-align: center;
                            border-bottom: 1px solid #000;
                            padding-bottom: 4px;
                        }

                        h2, h3, h4, h5, h6 {
                            font-family: 'Calibri', sans-serif;
                            font-size: 12px; /* headings 11 */
                            font-weight: bold; /* bold for all subheadings */
                            margin-top: 15px;
                            margin-bottom: 4px;
                            border-bottom: 1px solid #000;
                            padding-bottom: 4px;
                        }

                        span {
                            font-family: 'Calibri', sans-serif;
                            font-size: 12px;
                            display: block;
                            text-align: center;
                        }

                        p {
                            margin-top: 0px;
                            padding-top: 0px;
                            margin-bottom: 0px;
                            padding-bottom: 0px;
                        }

                        ul {
                            margin-top: 0px;
                            padding-top: 0px;
                            margin-bottom: 0px;
                            padding-bottom: 0px;
                        }

                        li {
                            margin-top: 0px;
                            padding-top: 0px;
                            margin-bottom: 0px;
                            padding-bottom: 0px;
                        }
                    </style>
                </head>
                <body>
                    $html
                </body>
            </html>
        ");

        $userName = preg_replace('/[\/\\\\]/', '-', $this->candidateName ?: 'Name');
        $role = preg_replace('/[\/\\\\]/', '-', $this->role ?: 'Role');

        $fileName = "{$userName} - {$role}.pdf";

        return response()->streamDownload(
            fn() => print($pdf->output()),
            $fileName
        );
    }

    private function extractPdf($path)
    {
        try {
            $parser = new Parser();
            $pdf = $parser->parseFile($path);
            return $pdf->getText();
        } catch (\Exception $e) {
            return null;
        }
    }

    public function render()
    {
        return view('livewire.resume.demo');
    }
}
