<?php

namespace App\Actions\Resume;

use App\Data\CoverLetterData;
use App\Enums\ResumeTemplate;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Carbon;

class RenderCoverLetterPdf
{
    public function handle(CoverLetterData $letter, ResumeTemplate $template = ResumeTemplate::Modern): string
    {
        $locale = $letter->language->locale();

        return Pdf::loadView('pdf.cover-letter.modern', [
            'letter' => $letter,
            'metrics' => $template->metrics(),
            'font' => config('resume.pdf.font'),
            'locale' => $locale,
            // La fecha se pone al generar el PDF, no al escribir la carta: el
            // usuario puede descargarla días después de haberla creado.
            'date' => Carbon::now()->locale($locale)->isoFormat(
                $letter->language->pick('D [de] MMMM [de] YYYY', 'MMMM D, YYYY'),
            ),
        ])
            ->setPaper(config('resume.pdf.paper'), 'portrait')
            ->setOptions([
                'defaultFont' => config('resume.pdf.font'),
                'isRemoteEnabled' => false,
                'isHtml5ParserEnabled' => true,
                'dpi' => 96,
            ])
            ->output();
    }
}
