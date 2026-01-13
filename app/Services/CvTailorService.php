<?php

namespace App\Services;

use OpenAI\Laravel\Facades\OpenAI;
use Barryvdh\DomPDF\Facade\Pdf;
use Smalot\PdfParser\Parser;
use RuntimeException;

class CvTailorService
{
    /**
     * Full resume tailoring flow (HTML with header)
     */
    public function tailorResume(string $resumePath, string $jobDescription): array
    {
        $cvText = $this->extractCvText($resumePath);

        $name = $this->extractNameFromCv($cvText);
        $contactLine = $this->extractContactLine($cvText);

        $requirements = $this->extractRequirements($jobDescription);

        $bodyHtml = $this->tailor(
            cvText: $cvText,
            jobOffer: $jobDescription,
            req: $requirements
        );

        return [
            'html' => $this->buildHeaderHtml(
                $name ?: 'Insert name here',
                $contactLine ?: 'Insert city, country • Insert email • Insert phone'
            ) . $bodyHtml,
            'cvText' => $cvText,
            'name' => $name,
        ];
    }

    /**
     * Download final PDF
     */
    public function downloadPdf(
        string $tailoredHtml,
        string $candidateName,
        string $jobDescription
    ) {
        $pdf = $this->generatePdf($tailoredHtml);

        return response()->streamDownload(
            fn() => print($pdf),
            $this->buildFileName(
                $candidateName,
                $this->extractRole($jobDescription)
            )
        );
    }

    /**
     * -----------------------------
     * Header
     * -----------------------------
     */
    private function buildHeaderHtml(string $name, string $contactLine): string
    {
        return <<<HTML
            <h1>{$name}</h1>
            <span>{$contactLine}</span>
        HTML;
    }

    /**
     * -----------------------------
     * File helpers
     * -----------------------------
     */
    private function buildFileName(?string $name, ?string $role): string
    {
        $name = ($name && $name !== 'Unknown')
            ? $name
            : 'Unknown Name';

        $role = ($role && $role !== 'Unknown')
            ? $role
            : null;

        $file = $role
            ? "{$name} - {$role}.pdf"
            : "{$name}.pdf";

        return preg_replace('/[\/\\\\]/', '-', $file);
    }

    public function extractNameFromCv(string $cvText): string
    {
        $prompt = "
            Extract the candidate's full name.
            Return ONLY the name, if you cannot find the name, return \"Unknown\".

            --- CV ---
            {$cvText}
            ";

        return trim(
            OpenAI::chat()->create([
                'model' => 'gpt-4.1',
                'messages' => [['role' => 'user', 'content' => $prompt]],
            ])->choices[0]->message->content
        );
    }

    /**
     * -----------------------------
     * CV extraction
     * -----------------------------
     */
    private function extractCvText(string $path): string
    {
        if (!file_exists($path)) {
            throw new RuntimeException('Resume file not found.');
        }

        if (str_ends_with(strtolower($path), '.pdf')) {
            return $this->extractPdf($path);
        }

        return trim(file_get_contents($path));
    }

    private function extractPdf(string $path): string
    {
        try {
            return trim((new Parser())->parseFile($path)->getText());
        } catch (\Throwable) {
            throw new RuntimeException('Unable to read resume PDF.');
        }
    }

    /**
     * -----------------------------
     * PDF generation
     * -----------------------------
     */
    private function generatePdf(string $html): string
    {
        return Pdf::loadHTML("
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
        ")->output();
    }

    /**
     * -----------------------------
     * AI – Extraction
     * -----------------------------
     */
    private function extractRequirements(string $jobOffer): array
    {
        $prompt = "
        Extract key job requirements.
        Respond ONLY with valid JSON.
        Return an array. If none are found, return [].

        --- JOB DESCRIPTION ---
        {$jobOffer}
    ";

        $response = OpenAI::chat()->create([
            'model' => 'gpt-4.1',
            'messages' => [['role' => 'user', 'content' => $prompt]],
        ])->choices[0]->message->content ?? '';

        // Limpieza básica por si vienen fences ```json
        $response = trim($response);
        $response = preg_replace('/^```json|```$/i', '', $response);

        $decoded = json_decode($response, true);

        // 🔐 Fallback absoluto
        if (!is_array($decoded)) {
            return [];
        }

        return $decoded;
    }

    private function extractContactLine(string $cvText): ?string
    {
        $prompt = "
            Extract location, email and phone.
            Return ONE line separated by •.

            --- CV ---
            {$cvText}
            ";

        return trim(
            OpenAI::chat()->create([
                'model' => 'gpt-4.1',
                'messages' => [['role' => 'user', 'content' => $prompt]],
            ])->choices[0]->message->content
        );
    }

    public function extractRole(string $jobOffer): string
    {
        $prompt = "
            Extract ONLY the job title.
            Return \"Unknown\" if unclear.

            --- JOB DESCRIPTION ---
            {$jobOffer}
        ";

        return trim(
            OpenAI::chat()->create([
                'model' => 'gpt-4.1',
                'messages' => [['role' => 'user', 'content' => $prompt]],
            ])->choices[0]->message->content
        );
    }

    /**
     * -----------------------------
     * AI – Tailoring
     * -----------------------------
     */
    public function tailor(string $cvText, string $jobOffer, array $req): string
    {
        $prompt = "
            You are a professional CV rewriting engine.

            Rewrite the CV to align with the job requirements.

            ====================
            GLOBAL STRUCTURE RULES
            ====================

            - Section titles MUST ALWAYS be rendered as <h2> with bold text.
            - Section titles MUST NOT be bullet points.
            - Return ONLY clean HTML.
            - Do NOT include a profile or summary.
            - Do NOT include name or contact details.
            - Never invent information.
            - Use ONLY these HTML tags:
            h1, h2, p, ul, li, strong, em, u, span, br.

            ====================
            SCOPE OF STRICT FORMATTING (VERY IMPORTANT)
            ====================

            The STRICT formatting rules described below apply ONLY to:
            - Work Experience entries
            - Education entries

            They DO NOT apply to:
            - Skills
            - Technical Skills
            - Certifications
            - Academic Projects
            - Languages
            - Tools
            - Soft Skills
            - Any other section not explicitly listed above

            All other sections MUST be rendered in a normal, natural CV format
            using paragraphs and/or bullet points as appropriate.

            ====================
            WORK EXPERIENCE FORMAT (STRICT – NO EXCEPTIONS)
            ====================

            Each work experience entry MUST follow EXACTLY this structure:

            1) FIRST <p> — TITLE LINE
            - Wrapped in <strong>
            - Content ONLY:
            Job Title – Company / Experience Name
            - MUST NOT include:
            Dates, location, descriptions, responsibilities, skills, or extra context

            2) SECOND <p> — META LINE
            - Plain text (NO <strong>)
            - Content ONLY:
            Location — Date range
            - Date format MUST be:
            Month YYYY – Month YYYY
            OR
            Month YYYY – Present

            3) AFTER these two <p>:
            - Responsibilities / achievements MUST be bullet points (<ul><li>)
            - MAX 5 bullets per role

            ====================
            EDUCATION FORMAT (STRICT, WITH DESCRIPTION ALLOWED)
            ====================

            Each Education entry MUST follow EXACTLY this structure:

            1) FIRST <p>
            - Wrapped in <strong>
            - Content:
            Degree / Program – Institution

            2) SECOND <p>
            - Plain text (NO <strong>)
            - Content:
            Location — Date range OR expected graduation

            3) OPTIONAL THIRD <p> (ALLOWED AND ENCOURAGED):
            - Plain text
            - Used to describe:
            - Field of study
            - Campus
            - Relevant coursework
            - MUST be concise
            - MUST reflect information present in the original CV
            - MUST NOT include bullets

            ====================
            ALL OTHER SECTIONS (NORMAL FORMAT)
            ====================

            For sections such as Skills, Academic Projects, Certifications, Languages, etc.:

            - Use a natural CV format
            - Bullet points are allowed and encouraged where appropriate
            - No strict paragraph structure is required
            - Do NOT force the Experience/Education format here

            ====================
            REQUIREMENTS TO TAILOR AGAINST
            ====================

            " . json_encode($req, JSON_PRETTY_PRINT) . "

            --- ORIGINAL CV ---
            {$cvText}

            --- JOB DESCRIPTION ---
            {$jobOffer}
        ";

        return trim(
            OpenAI::chat()->create([
                'model' => 'gpt-4.1',
                'messages' => [['role' => 'user', 'content' => $prompt]],
            ])->choices[0]->message->content
        );
    }
}
