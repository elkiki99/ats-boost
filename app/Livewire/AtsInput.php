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

    public $tailored = '';

    public function uploadResume($resume)
    {
        $this->resume = $resume;
    }

    public function startTailoring()
    {
        $this->validate();

        // disparar evento global
        $this->dispatch('tailoring-started');
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

        // 4. Close in-progress modal
        $this->modal('tailoring-in-progress')->close();

        // 5. Show result modal
        $this->modal('tailoring-result')->show();
    }

    public function downloadPdf()
    {
        $html = $this->tailored; // YA ES HTML PERFECTO

        $pdf = Pdf::loadHTML("
            <html>
                <head>
                    <style>
                        body {
                            font-family: 'Calibri', sans-serif;
                            font-size: 11px; /* normal text */
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
                            font-size: 11px; /* headings 11 */
                            font-weight: bold; /* bold for all subheadings */
                            margin-top: 16px;
                            margin-bottom: 4px;
                            border-bottom: 1px solid #000;
                            padding-bottom: 4px;
                        }

                        span {
                            font-family: 'Calibri', sans-serif;
                            font-size: 11px;
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
