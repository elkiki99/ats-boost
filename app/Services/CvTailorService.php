<?php

namespace App\Services;

use OpenAI\Laravel\Facades\OpenAI;

class CvTailorService
{
    /**
     * Extract requirements (skills, tools, must-haves, etc.)
     */
    public function extractRequirements(string $jobOffer): array
    {
        $prompt = "
            You are an HR and ATS expert.

            TASK:
            Extract all key job requirements from the job description.

            LANGUAGE RULE:
            - Detect the language of the job description.
            - Output the extracted requirements IN THE SAME LANGUAGE.

            RETURN FORMAT (IMPORTANT):
            Respond ONLY with valid JSON, no markdown:

            {
                \"required_skills\": [\"...\"],
                \"responsibilities\": [\"...\"],
                \"tools\": [\"...\"],
                \"soft_skills\": [\"...\"],
                \"must_haves\": [\"...\"]
            }

            Do NOT add extra fields.
            Do NOT invent items.

            --- JOB DESCRIPTION ---
            $jobOffer
        ";

        $response = OpenAI::chat()->create([
            'model' => 'gpt-4.1',
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);

        return json_decode($response->choices[0]->message->content, true);
    }

    /**
     * Tailor CV to match job requirements and return HTML
     */
    public function tailor(string $cvText, string $jobOffer, array $req): string
    {
        $prompt = "
            You are a professional CV rewriting engine.

            TASK:
            Rewrite the CV so it aligns strongly with the job description requirements,
            while keeping ALL content truthful.

            ALLOWED:
            - Rewriting sentences for clarity
            - Reordering sections
            - Emphasizing info aligned with the requirements
            - Changing bullet points to match required skills if you can infer them from the CV
            - Inferring skills that are implicitly present
              (e.g., 'built a website with Laravel' → Laravel, PHP, APIs)

            FORBIDDEN:
            - Inventing information not present in the CV
            - Adding tools or jobs the person never used
            - Adding more than 5 bullet points per job experience

            LANGUAGE RULE:
            - Detect the language of the job description.
            - The CV should be in the language of the job description, not the language of the CV neccesarily.

            FORMAT RULES (IMPORTANT):
            - Return ONLY clean HTML.
            - The persons name must ALWAYS be an <h1> on top of the page.
            - Section titles must be capitalized like:
              Education, Experience, Skills, Projects, Certifications, Languages.
            - No uppercase titles like EXPERIENCE.
            - Use <h1>, <h2>, <p>, <ul>, <li>.
            - Section titles must be <h2> with bold text: <h2><strong>Education</strong></h2>
            - Job titles, companies and study institutions MUST be bold inside <p>
            Example:
            <p><strong>Software Developer – Company Name</strong></p>
            <p>City, Country — 2023–Present</p>
            - Keep a professional clean layout.

            EXPERIENCE BULLET RULES (CRITICAL):
            - The job title, company, location and dates MUST NOT be bullets.
            - They must appear as normal paragraphs (<p>), never inside <ul>.
            - ONLY the action items / responsibilities must use <ul><li>.

            PERSONAL INFO RULE (CRITICAL):
            - The contact information line must ALWAYS be rendered inside a single <span>.
            - It must ALWAYS be on one line.
            - The model must NEVER break this line into multiple lines.
            - The items must be separated by • (middle dot).

            HTML SANITATION RULE:
            - Output ONLY the following HTML tags: h1, h2, h3, p, ul, li, strong, em, u, span, br.
            - DO NOT use div, section, article, style or inline CSS except the contact <span>.
            - No inline margin or padding inside the content.

            USE THESE REQUIREMENTS TO TAILOR:
            " . json_encode($req, JSON_PRETTY_PRINT) . "

            --- ORIGINAL CV ---
            $cvText

            --- JOB DESCRIPTION ---
            $jobOffer
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