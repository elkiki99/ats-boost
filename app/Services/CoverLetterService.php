<?php

namespace App\Services;

use OpenAI\Laravel\Facades\OpenAI;
use Barryvdh\DomPDF\Facade\Pdf;
use Smalot\PdfParser\Parser;
use RuntimeException;

class CoverLetterService
{
    public function generatePdf(string $html): string
    {
        return Pdf::loadHTML("
            <html>
                <head>
                    <style>
                        body {
                            font-family: Calibri, sans-serif;
                            font-size: 12px;
                            line-height: 1.5;
                            margin: 30px;
                        }
                        h1 {
                            font-size: 18px;
                            margin-bottom: 4px;
                        }
                        h2 {
                            font-size: 13px;
                            font-weight: normal;
                            margin-bottom: 16px;
                        }
                        p {
                            margin: 0 0 10px 0;
                        }
                    </style>
                </head>
                <body>
                    {$html}
                </body>
            </html>
        ")->output();
    }

    public function extractCvText(string $path): string
    {
        if (!file_exists($path)) {
            throw new RuntimeException('CV file not found.');
        }

        if (str_ends_with(strtolower($path), '.pdf')) {
            return $this->extractPdf($path);
        }

        return trim(file_get_contents($path));
    }

    private function extractPdf(string $path): string
    {
        try {
            $parser = new Parser();
            $pdf = $parser->parseFile($path);
            return trim($pdf->getText());
        } catch (\Throwable) {
            throw new RuntimeException('Unable to extract text from PDF CV.');
        }
    }

    /**
     * Infer candidate name and role from CV using AI
     */
    public function inferCandidateProfile(string $cvText): array
    {
        $prompt = "
            Extract the candidate's full name and primary professional role from the CV below.

            RULES:
            - Use ONLY the CV content.
            - Do NOT guess missing data.
            - If unclear, return null values.
            - Output STRICT JSON.

            JSON FORMAT:
            {
                \"name\": string|null,
                \"role\": string|null
            }

            --- CV ---
            {$cvText}
        ";

        $response = OpenAI::chat()->create([
            'model' => 'gpt-4.1',
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);

        return json_decode(
            $response->choices[0]->message->content,
            true,
            flags: JSON_THROW_ON_ERROR
        );
    }

    public function buildFileName(
        ?string $name,
        ?string $role,
        ?string $company = null
    ): string {
        $name ??= 'Cover Letter';
        $rolePart = $role ? " - {$role}" : '';
        $companyPart = $company ? " - {$company}" : '';

        return preg_replace(
            '/[\/\\\\]/',
            '-',
            "{$name}{$rolePart}{$companyPart}.pdf"
        );
    }

    /**
     * Generate cover letter with inferred header
     */
    public function generateCoverLetter(
        string $description,
        string $cvText,
        ?string $company = null
    ): string {
        $profile = $this->inferCandidateProfile($cvText);

        $name = $profile['name'] ?? 'Candidate';
        $role = $profile['role'] ?? null;

        $companyInstruction = $company
            ? "The company name is: {$company}. Always use this name."
            : "
                The company name is NOT explicitly provided.
                If clearly mentioned in the job description, you may use it.
                Otherwise, do NOT guess.
            ";

        $header = "<h1>{$name}</h1>";
        if ($role || $company) {
            $line = trim("{$role}" . ($company ? " · {$company}" : ''));
            $header .= "<h2>{$line}</h2>";
        }

        $prompt = "
            You are a professional career coach and senior recruiter.

            TASK:
            Write a formal, professional cover letter following the classic business structure.

            LANGUAGE:
            Same language as the job description.

            STRUCTURE (MANDATORY):
            1. Header section:
            - Date (today)
            - Company name (if known)
            - Job title (if known)

            2. Greeting:
            - Address the company or hiring team professionally.
            - If no contact name is available, use a neutral greeting.

            3. Opening paragraph:
            - Clearly state the position you are applying for.
            - Explain why you are interested in this role and company.
            - Briefly state why you are a strong fit.

            4. Middle paragraph(s):
            - Provide 1–2 specific examples from the CV that demonstrate relevance.
            - Do NOT repeat the full resume.
            - Clearly connect experience to the role requirements.

            5. Closing paragraph:
            - Reiterate interest and enthusiasm.
            - State willingness to discuss further.
            - Thank the reader for their time.

            6. Sign-off:
            - Professional closing
            - Candidate name

            RULES:
            - Use ONLY information present in the CV.
            - Do NOT invent experience, companies, or skills.
            - Be confident, concise, and specific.
            - Maximum 4 short body paragraphs.
            - Clean HTML only.
            - Allowed tags: h1, h2, p, strong, em, br

            HEADER (MANDATORY):
            Use the following header EXACTLY as provided:
            {$header}

            --- CV ---
            {$cvText}

            --- JOB DESCRIPTION ---
            {$description}

            {$companyInstruction}
            ";

        $response = OpenAI::chat()->create([
            'model' => 'gpt-4.1',
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);

        return trim($response->choices[0]->message->content);
    }
}
