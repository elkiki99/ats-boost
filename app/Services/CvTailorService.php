<?php

namespace App\Services;

use OpenAI\Laravel\Facades\OpenAI;

class CvTailorService
{
    public function tailor(string $cvText, string $jobOffer): string
    {
        $requirements = $this->extractRequirements($jobOffer);

        $tailoredCv = $this->alignCv($cvText, $requirements);

        return $tailoredCv;
    }

    private function extractRequirements(string $jobOffer): string
    {
        $prompt = "
            You are an ATS & HR expert.

            Extract the essential skills, responsibilities, experience, technologies, and soft skills from the following job offer.

            Return ONLY a bullet list. No commentary.

            JOB OFFER:
            $jobOffer
        ";

        $response = OpenAI::chat()->create([
            'model'   => 'gpt-4.1',
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);

        return $response->choices[0]->message->content;
    }

    private function alignCv(string $cvText, string $requirements): string
    {
        $prompt = "
            You are a Resume Tailoring Engine.

            RULES:
            - Do NOT invent information.
            - Modify only what's already in the CV.
            - Emphasize experience that aligns with the job requirements.
            - Use strong action verbs.
            - Harvard-style concise formatting.
            - Only rewrite sections that benefit alignment.
            - DO NOT add responsibilities or achievements that the user does not already have.

            USER CV:
                $cvText

            JOB REQUIREMENTS:
            $requirements

            Return the tailored CV in clean text format.
        ";

        $response = OpenAI::chat()->create([
            'model'   => 'gpt-4.1',
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);

        return $response->choices[0]->message->content;
    }
}
