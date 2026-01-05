<?php

// namespace App\Services;

// use OpenAI\Laravel\Facades\OpenAI;

// class CvTailorService
// {

//     public function tailorResume(string $resumePath, string $jobDescription): string
//     {
//         $cvText = $this->extractCvText($resumePath);

//         $name = $this->extractCandidateName($cvText) ?? 'Insert name here';
//         $contactLine = $this->extractContactLine($cvText)
//             ?? 'Insert city, country • Insert email • Insert phone';

//         $requirements = $this->extractRequirements($jobDescription);

//         $bodyHtml = $this->tailor(
//             cvText: $cvText,
//             jobOffer: $jobDescription,
//             requirements: $requirements
//         );

//         return $this->buildHeaderHtml($name, $contactLine)
//             . $bodyHtml;
//     }

//     /**
//      * Extract requirements (skills, tools, must-haves, etc.)
//      */
//     public function extractRequirements(string $jobOffer): array
//     {
//         $prompt = "
//             You are an HR and ATS expert.

//             TASK:
//             Extract all key job requirements from the job description.

//             LANGUAGE RULE:
//             - Detect the language of the job description.
//             - Output the extracted requirements IN THE SAME LANGUAGE.

//             RETURN FORMAT (IMPORTANT):
//             Respond ONLY with valid JSON, no markdown:

//             {
//                 \"required_skills\": [\"...\"],
//                 \"responsibilities\": [\"...\"],
//                 \"tools\": [\"...\"],
//                 \"soft_skills\": [\"...\"],
//                 \"must_haves\": [\"...\"]
//             }

//             Do NOT add extra fields.
//             Do NOT invent items.

//             --- JOB DESCRIPTION ---
//             $jobOffer
//         ";

//         $response = OpenAI::chat()->create([
//             'model' => 'gpt-4.1',
//             'messages' => [
//                 ['role' => 'user', 'content' => $prompt],
//             ],
//         ]);

//         return json_decode($response->choices[0]->message->content, true);
//     }

//     private function extractContactLine(string $cvText): ?string
//     {
//         $prompt = "
//             Extract the candidate's location(s), email and phone.
//             Return a SINGLE LINE separated by ' • '.
//             Example:
//             City, Country • Area, ZIP • email@example.com • +00-000-0000

//             --- CV ---
//             {$cvText}
//         ";

//         return trim(
//             OpenAI::chat()->create([
//                 'model' => 'gpt-4.1',
//                 'messages' => [['role' => 'user', 'content' => $prompt]],
//             ])->choices[0]->message->content
//         );
//     }

//     public function extractNameFromCv(string $cvText): string
//     {
//         $prompt = "
//             You are an expert at reading resumes (CVs).
//             TASK:
//             From the following CV text (in any language), identify the full name of the candidate.
//             RETURN:
//             Return ONLY the full name. Do not add extra text or punctuation.
//             If you cannot reliably identify a name, return \"Unknown\".

//             --- CV TEXT ---
//             $cvText
//         ";

//         $response = OpenAI::chat()->create([
//             'model' => 'gpt-4.1',
//             'messages' => [
//                 ['role' => 'user', 'content' => $prompt],
//             ],
//         ]);

//         return trim($response->choices[0]->message->content);
//     }

//     public function extractRole(string $jobOffer): string
//     {
//         $prompt = "
//             You are an HR expert with ATS experience.

//             TASK:
//             Extract ONLY the job title / role being offered.

//             RULES:
//             - Output ONLY the job title.
//             - Do NOT add explanations.
//             - Detect the language and output the role in the SAME LANGUAGE.
//             - If multiple titles appear, choose the main/primary one.
//             - Never invent a role.
//             - If no clear role is found, answer: \"Unknown\".

//             --- JOB DESCRIPTION ---
//             $jobOffer
//         ";

//         $response = OpenAI::chat()->create([
//             'model' => 'gpt-4.1',
//             'messages' => [
//                 ['role' => 'user', 'content' => $prompt],
//             ],
//         ]);

//         return trim($response->choices[0]->message->content);
//     }

//     private function buildHeaderHtml(?string $name, ?string $contactLine): string
//     {
//         $name ??= 'Insert name here';
//         $contactLine ??= 'Insert location • Insert email • Insert phone';

//         return "
//         <h1>{$name}</h1>
//         <span>{$contactLine}</span>
//     ";
//     }

//     public function downloadPdf(string $tailoredHtml)
//     {
//         $pdf = $this->generatePdf($tailoredHtml);

//         return response()->streamDownload(
//             fn() => print($pdf),
//             $this->buildFileName(
//                 $this->extractCandidateName($tailoredHtml),
//                 $this->extractRoleFromJob($tailoredHtml)
//             )
//         );
//     }

//     /**
//      * Tailor CV to match job requirements and return HTML
//      */
//     public function tailor(string $cvText, string $jobOffer, array $req): string
//     {
//         $prompt = "
//             You are a professional CV rewriting engine.

//             TASK:
//             Rewrite the CV so it aligns strongly with the job description requirements,
//             while keeping ALL content truthful.

//             ALLOWED:
//             - Rewriting sentences for clarity
//             - Reordering sections
//             - Emphasizing info aligned with the requirements
//             - Deleting irrelevant info
//             - You ARE allowed to rewrite, merge, remove, or replace existing bullet points.
//             - You ARE allowed to create new bullet points ONLY if they are logically supported by facts in the CV.
//             - If a bullet is irrelevant to the job requirements, replace it with another bullet that is supported by the CV.
//             - Reformulate bullets to match the tone, keywords, and focus of the job requirements.
//             - Maximum 5 bullets per job, but they can be completely different from the original ones.
//             - Inferring skills that are implicitly present
//               (e.g., 'built a website with Laravel' → Laravel, PHP, APIs)

//             FORBIDDEN:
//             - Make up information not present in the CV
//             - Adding tools or jobs the person never used
//             - Adding more than 5 bullet points per job experience
//             - Using another language other than the job description's language

//             LANGUAGE RULE:
//             - ALWAYS write the tailored CV in the SAME language as the job description.
//             - DO NOT translate the job description.
//             - DO NOT translate the CV unless necessary to match the job description’s language.
//             - If the job description is in English, the CV MUST be in English.

//             FORMAT RULES (IMPORTANT):
//             - Return ONLY clean HTML.
//             - The persons name must ALWAYS be an <h1> on top of the page.
//             - Section titles must be capitalized like:
//               Education, Educación, Etc...
//             - No uppercase titles like EXPERIENCE, EXPERIENCIA, ETC.
//             - Use <h1>, <h2>, <p>, <ul>, <li>.
//             - Section titles must be <h2> with bold text: <h2><strong>Education</strong></h2>
//             - Job titles, companies and study institutions MUST be bold inside <p>
//             Example:
//             <p><strong>Software Developer – Company Name</strong></p>
//             <p>City, Country — 2023–Present</p>
//             - Keep a professional clean layout.

//             EXPERIENCE BULLET RULES (CRITICAL):
//             - The job title, company, location and dates MUST NOT be bullets.
//             - They must appear as normal paragraphs (<p>), never inside <ul>.
//             - ONLY the action items / responsibilities must use <ul><li>.

//             PERSONAL INFO RULE (CRITICAL):
//             - The contact information line must ALWAYS be rendered inside a single <span>.  
//             - It must ALWAYS be on one line.
//             - The model must NEVER break this line into multiple lines.
//             - The items must be separated by • (middle dot).

//             HTML SANITATION RULE:
//             - Output ONLY the following HTML tags: h1, h2, h3, p, ul, li, strong, em, u, span, br.
//             - DO NOT use div, section, article, style or inline CSS except the contact <span>.
//             - No inline margin or padding inside the content.

//             USE THESE REQUIREMENTS TO TAILOR:
//             " . json_encode($req, JSON_PRETTY_PRINT) . "

//             --- ORIGINAL CV ---
//             $cvText

//             --- JOB DESCRIPTION ---
//             $jobOffer
//         ";

//         $response = OpenAI::chat()->create([
//             'model' => 'gpt-4.1',
//             'messages' => [
//                 ['role' => 'user', 'content' => $prompt],
//             ],
//         ]);

//         return trim($response->choices[0]->message->content);
//     }
// }



// <?php

// namespace App\Services;

// use OpenAI\Laravel\Facades\OpenAI;
// use Barryvdh\DomPDF\Facade\Pdf;
// use Smalot\PdfParser\Parser;
// use RuntimeException;

// class CvTailorService
// {
//     /**
//      * Full resume tailoring flow (HTML only, no header)
//      */
//     public function tailorResume(string $resumePath, string $jobDescription): string
//     {
//         $cvText = $this->extractCvText($resumePath);

//         $name = $this->extractCandidateName($cvText) ?? 'Insert name here';
//         $contactLine = $this->extractContactLine($cvText)
//             ?? 'Insert city, country • Insert email • Insert phone';

//         $requirements = $this->extractRequirements($jobDescription);

//         $bodyHtml = $this->tailor(
//             cvText: $cvText,
//             jobOffer: $jobDescription,
//             requirements: $requirements
//         );

//         return $this->buildHeaderHtml($name, $contactLine)
//             . $bodyHtml;
//     }

//     /**
//      * Build and stream final PDF
//      */
//     public function downloadPdf(string $tailoredHtml)
//     {
//         $pdf = $this->generatePdf($tailoredHtml);

//         return response()->streamDownload(
//             fn() => print($pdf),
//             $this->buildFileName(
//                 $this->extractCandidateName($tailoredHtml),
//                 $this->extractRoleFromJob($tailoredHtml)
//             )
//         );
//     }

//     /**
//      * -----------------------------
//      * Header
//      * -----------------------------
//      */

//     private function buildHeaderHtml(?string $name, ?string $contactLine): string
//     {
//         $name ??= 'Insert name here';
//         $contactLine ??= 'Insert location • Insert email • Insert phone';

//         return "
//         <h1>{$name}</h1>
//         <span>{$contactLine}</span>
//     ";
//     }

//     /**
//      * -----------------------------
//      * Infrastructure
//      * -----------------------------
//      */

//     private function extractCvText(string $path): string
//     {
//         if (!file_exists($path)) {
//             throw new RuntimeException('Resume file not found.');
//         }

//         if (str_ends_with(strtolower($path), '.pdf')) {
//             return $this->extractPdf($path);
//         }

//         return trim(file_get_contents($path));
//     }

//     private function extractPdf(string $path): string
//     {
//         try {
//             return trim((new Parser())->parseFile($path)->getText());
//         } catch (\Throwable) {
//             throw new RuntimeException('Unable to read resume PDF.');
//         }
//     }

//     private function generatePdf(string $html): string
//     {
//         return Pdf::loadHTML("
// <html>
//     <head>
//         <style>
//             body {
//                 font-family: 'Calibri', sans-serif;
//                 font-size: 12px; /* normal text */
//                 font-weight: normal;
//                 line-height: 1.4;
//                 margin: 30px;
//             }

//             h1 {
//                 font-family: 'Calibri', sans-serif;
//                 font-size: 14px; /* Calibri bold 14 */
//                 font-weight: bold;
//                 margin-bottom: 4px;
//                 text-align: center;
//                 border-bottom: 1px solid #000;
//                 padding-bottom: 4px;
//             }

//             h2, h3, h4, h5, h6 {
//                 font-family: 'Calibri', sans-serif;
//                 font-size: 12px; /* headings 11 */
//                 font-weight: bold; /* bold for all subheadings */
//                 margin-top: 15px;
//                 margin-bottom: 4px;
//                 border-bottom: 1px solid #000;
//                 padding-bottom: 4px;
//             }

//             span {
//                 font-family: 'Calibri', sans-serif;
//                 font-size: 12px;
//                 display: block;
//                 text-align: center;
//             }

//             p {
//                 margin-top: 0px;
//                 padding-top: 0px;
//                 margin-bottom: 0px;
//                 padding-bottom: 0px;
//             }

//             ul {
//                 margin-top: 0px;
//                 padding-top: 0px;
//                 margin-bottom: 0px;
//                 padding-bottom: 0px;
//             }

//             li {
//                 margin-top: 0px;
//                 padding-top: 0px;
//                 margin-bottom: 0px;
//                 padding-bottom: 0px;
//             }
//         </style>
//     </head>
//     <body>
//         $html
//     </body>
// </html>
//         ")->output();
//     }

//     private function buildFileName(?string $name, ?string $role): string
//     {
//         $name ??= 'Tailored Resume';
//         $role ??= 'Role';

//         return preg_replace(
//             '/[\/\\\\]/',
//             '-',
//             "{$name} - {$role}.pdf"
//         );
//     }

//     /**
//      * -----------------------------
//      * AI – Semantic Extraction
//      * -----------------------------
//      */

//     private function extractCandidateName(string $cvText): ?string
//     {
//         $prompt = "
//             Extract the candidate's full name.
//             Return ONLY the name.

//             --- CV ---
//             {$cvText}
//         ";

//         return trim(
//             OpenAI::chat()->create([
//                 'model' => 'gpt-4.1',
//                 'messages' => [['role' => 'user', 'content' => $prompt]],
//             ])->choices[0]->message->content
//         );
//     }

//     private function extractContactLine(string $cvText): ?string
//     {
//         $prompt = "
//             Extract the candidate's location(s), email and phone.
//             Return a SINGLE LINE separated by ' • '.
//             Example:
//             City, Country • Area, ZIP • email@example.com • +00-000-0000

//             --- CV ---
//             {$cvText}
//         ";

//         return trim(
//             OpenAI::chat()->create([
//                 'model' => 'gpt-4.1',
//                 'messages' => [['role' => 'user', 'content' => $prompt]],
//             ])->choices[0]->message->content
//         );
//     }

//     private function extractRequirements(string $jobOffer): array
//     {
//         $prompt = "
//             Extract key job requirements.
//             Respond ONLY with valid JSON.

//             --- JOB DESCRIPTION ---
//             {$jobOffer}
//         ";

//         return json_decode(
//             OpenAI::chat()->create([
//                 'model' => 'gpt-4.1',
//                 'messages' => [['role' => 'user', 'content' => $prompt]],
//             ])->choices[0]->message->content,
//             true
//         );
//     }

//     /**
//      * -----------------------------
//      * AI – Tailoring Engine
//      * -----------------------------
//      */

//     private function tailor(string $cvText, string $jobOffer, array $requirements): string
//     {
//         $prompt = "
//             You are a professional CV rewriting engine.

//             Rewrite the CV to align with the job requirements.

//             RULES ABOUT BULLETS:
//             - Use bullet points ONLY for:
//             - Responsibilities / achievements inside roles
//             - Skills
//             - Certifications
//             - Technical knowledge
//             - Job titles, company names and dates MUST NOT be bullets.
//             - Section titles MUST NOT be bullets.

//             Do NOT include a profile or summary.
//             Do NOT include name or contact details.
//             Never invent information.
//             Return ONLY clean HTML.

//             REQUIREMENTS:
//             " . json_encode($requirements) . "

//             --- CV ---
//             {$cvText}

//             --- JOB DESCRIPTION ---
//             {$jobOffer}
//         ";

//         return trim(
//             OpenAI::chat()->create([
//                 'model' => 'gpt-4.1',
//                 'messages' => [['role' => 'user', 'content' => $prompt]],
//             ])->choices[0]->message->content
//         );
//     }
// }



// <?php

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
    public function tailorResume(string $resumePath, string $jobDescription): string
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

        return $this->buildHeaderHtml(
            $name ?: 'Insert name here',
            $contactLine ?: 'Insert city, country • Insert email • Insert phone'
        ) . $bodyHtml;
    }

    /**
     * Download final PDF
     */
    public function downloadPdf(
        string $tailoredHtml,
        string $cvText,
        string $jobDescription
    ) {
        $pdf = $this->generatePdf($tailoredHtml);

        return response()->streamDownload(
            fn() => print($pdf),
            $this->buildFileName(
                $this->extractNameFromCv($cvText),
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
        $name = $name && $name !== 'Unknown' ? $name : 'Tailored Resume';
        $role = $role && $role !== 'Unknown' ? $role : null;

        $file = $role
            ? "{$name} - {$role}.pdf"
            : "{$name}.pdf";

        return preg_replace('/[\/\\\\]/', '-', $file);
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

            --- JOB DESCRIPTION ---
            {$jobOffer}
        ";

        return json_decode(
            OpenAI::chat()->create([
                'model' => 'gpt-4.1',
                'messages' => [['role' => 'user', 'content' => $prompt]],
            ])->choices[0]->message->content,
            true
        );
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

    public function extractNameFromCv(string $cvText): string
    {
        $prompt = "
            Extract the candidate's full name.
            Return ONLY the name or \"Unknown\".

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
