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
            - Inferring skills that are implicitly present
              (e.g., 'built a website with Laravel' → Laravel, PHP, APIs)

            FORBIDDEN:
            - Inventing information not present in the CV
            - Adding tools or jobs the person never used

            LANGUAGE RULE:
            - Detect the language of the job description.
            - Rewrite the whole CV in the SAME language.

            FORMAT RULES (IMPORTANT):
            - Return ONLY clean HTML.
            - Section titles must be capitalized like:
              Education, Experience, Skills, Projects, Certifications, Languages.
            - No uppercase titles like EXPERIENCE.
            - Use <h1>, <h2>, <p>, <ul>, <li>.
            - Keep a professional clean layout.

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