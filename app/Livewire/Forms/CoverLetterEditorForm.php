<?php

namespace App\Livewire\Forms;

use App\Data\CoverLetterData;
use App\Enums\Language;
use Livewire\Form;

/**
 * Estado editable de la carta de presentación.
 *
 * @property array<string, mixed> $data
 */
class CoverLetterEditorForm extends Form
{
    /** @var array<string, mixed> */
    public array $data = [];

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'data.candidate_name' => ['required', 'string', 'max:120'],
            'data.role' => ['nullable', 'string', 'max:150'],
            'data.company' => ['nullable', 'string', 'max:120'],
            'data.greeting' => ['nullable', 'string', 'max:200'],
            'data.closing' => ['nullable', 'string', 'max:200'],

            'data.contact.location' => ['nullable', 'string', 'max:120'],
            'data.contact.email' => ['nullable', 'email:rfc', 'max:160'],
            'data.contact.phone' => ['nullable', 'string', 'max:40'],
            'data.contact.links' => ['array', 'max:5'],
            'data.contact.links.*' => ['nullable', 'string', 'max:120'],

            'data.paragraphs' => ['required', 'array', 'min:1', 'max:8'],
            'data.paragraphs.*' => ['required', 'string', 'max:1200'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function validationAttributes(): array
    {
        return [
            'data.candidate_name' => 'nombre',
            'data.paragraphs' => 'cuerpo de la carta',
            'data.paragraphs.*' => 'párrafo',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'data.paragraphs.required' => 'La carta no puede quedar vacía.',
            'data.paragraphs.*.required' => 'Ningún párrafo puede quedar vacío. Borralo si no lo querés.',
        ];
    }

    public function setLetter(CoverLetterData $letter): void
    {
        $this->data = $letter->toArray();
    }

    public function toData(): CoverLetterData
    {
        return CoverLetterData::from(
            $this->data,
            Language::tryFrom($this->data['language'] ?? '') ?? Language::Spanish,
        );
    }

    public function addParagraph(): void
    {
        $this->data['paragraphs'][] = '';
    }

    public function removeParagraph(int $index): void
    {
        unset($this->data['paragraphs'][$index]);

        $this->data['paragraphs'] = array_values($this->data['paragraphs'] ?? []);
    }

    public function moveParagraph(int $index, int $offset): void
    {
        $paragraphs = array_values($this->data['paragraphs'] ?? []);
        $target = $index + $offset;

        if (! isset($paragraphs[$index], $paragraphs[$target])) {
            return;
        }

        [$paragraphs[$index], $paragraphs[$target]] = [$paragraphs[$target], $paragraphs[$index]];

        $this->data['paragraphs'] = $paragraphs;
    }
}
