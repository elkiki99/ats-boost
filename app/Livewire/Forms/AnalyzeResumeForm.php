<?php

namespace App\Livewire\Forms;

class AnalyzeResumeForm extends ResumeUploadForm
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return $this->resumeRules();
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return $this->resumeMessages();
    }

    /**
     * @return array<string, string>
     */
    public function validationAttributes(): array
    {
        return $this->resumeAttributes();
    }
}
