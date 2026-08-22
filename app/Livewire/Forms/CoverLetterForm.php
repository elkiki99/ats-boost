<?php

namespace App\Livewire\Forms;

class CoverLetterForm extends ResumeUploadForm
{
    public string $description = '';

    public ?string $company = null;

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            ...$this->resumeRules(),
            ...$this->descriptionRules(),
            'company' => ['nullable', 'string', 'min:2', 'max:120'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            ...$this->resumeMessages(),
            ...$this->descriptionMessages(),
            'company.min' => 'El nombre de la empresa es demasiado corto.',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function validationAttributes(): array
    {
        return [
            ...$this->resumeAttributes(),
            'description' => 'descripción del puesto',
            'company' => 'empresa',
        ];
    }
}
