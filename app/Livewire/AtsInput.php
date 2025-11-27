<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\CvTailorService;
use Livewire\Attributes\Validate;
use Livewire\WithFileUploads;
use Smalot\PdfParser\Parser;
use Barryvdh\DomPDF\Facade\Pdf;

class AtsInput extends Component
{
    use WithFileUploads;

    #[Validate('required|mimes:pdf,txt|max:10240')]
    public $resume = null;

    #[Validate('required|string|min:50')]
    public $description = '';

    public $tailored = null;
    public $pdfPath = null;

    public function uploadResume($resume)
    {
        $this->resume = $resume;
    }

    public function tailorResume()
    {
        $this->validate();

        $path = $this->resume->getRealPath();

        $cvText = $this->extractPdf($path);

        if (!$cvText) {
            $this->addError('resume', 'No se pudo leer el PDF.');
            $this->modal('tailoring-in-progress')->show();
            return;
        }

        $service = app(CvTailorService::class);
        $result = $service->tailor($cvText, $this->description);

        $this->modal('tailoring-in-progress')->close();

        $this->tailorFinished($result);
    }

    public function tailorFinished($result)
    {
        $this->tailored = $result;
        $this->pdfPath = null;

        $this->modal('tailoring-result')->show();
    }
    
    public function downloadPdf()
    {
        $pdf = Pdf::loadHTML(nl2br(e($this->tailored)));

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'tailored-cv.pdf');
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
