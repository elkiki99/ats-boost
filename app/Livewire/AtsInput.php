<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use App\Services\CvTailorService;
use Smalot\PdfParser\Parser;
use Barryvdh\DomPDF\Facade\Pdf;

class AtsInput extends Component
{
    use WithFileUploads;

    #[Validate('required|mimes:pdf,txt|max:10240')]
    public $resume = null;

    #[Validate('required|string|min:50')]
    public $description = '';

    public $tailored = ''; // ← ÚNICO TEXTO FINAL

    public function uploadResume($resume)
    {
        $this->resume = $resume;
    }

    public function tailorResume()
    {
        $this->validate();

        // 1. Convert PDF to text
        $path = $this->resume->getRealPath();
        $cvText = $this->extractPdf($path);

        if (!$cvText) {
            $this->addError('resume', 'We were not able to read your PDF.');
            return;
        }

        $service = app(CvTailorService::class);

        // 2. Extract job requirements
        $requirements = $service->extractRequirements($this->description);

        // 3. Tailor CV using: CV TEXT + JOB OFFER + extracted requirements
        $this->tailored = $service->tailor(
            $cvText,
            $this->description,
            $requirements
        );

        // 4. Show result modal
        $this->modal('tailoring-result')->show();
    }

    public function downloadPdf()
    {
        $html = $this->tailored; // YA ES HTML PERFECTO

        $pdf = Pdf::loadHTML("
            <html>
                <head>
                    // <style>
                    //     body {
                    //         font-family: 'Times New Roman', serif;
                    //         font-size: 12px;
                    //         line-height: 1.4;
                    //         margin: 40px;
                    //     }
                    //     h1 { font-size: 26px; margin-bottom: 10px; }
                    //     h2 { font-size: 16px; margin-top: 24px; margin-bottom: 8px; }
                    //     p { margin-bottom: 6px; }
                    //     ul { margin-left: 20px; margin-bottom: 10px; }
                    //     li { margin-bottom: 4px; }
                    // </style>
                </head>
                <body>
                    $html
                </body>
            </html>
        ");

        return response()->streamDownload(
            fn() => print($pdf->output()),
            'cv.pdf'
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
        return view('livewire.ats-input');
    }
}
