<?php

namespace App\Livewire\Forms;

class TailorResumeForm extends ResumeUploadForm
{
    public string $description = '';

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [...$this->resumeRules(), ...$this->descriptionRules()];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [...$this->resumeMessages(), ...$this->descriptionMessages()];
    }

    /**
     * @return array<string, string>
     */
    public function validationAttributes(): array
    {
        return [...$this->resumeAttributes(), 'description' => 'descripción del puesto'];
    }
}
