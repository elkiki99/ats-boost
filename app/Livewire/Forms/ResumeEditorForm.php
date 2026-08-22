<?php

namespace App\Livewire\Forms;

use App\Data\ResumeData;
use App\Enums\Language;
use App\Enums\ResumeTemplate;
use Livewire\Form;

/**
 * Estado editable del currículum generado.
 *
 * El componente trabaja sobre un array anidado y no sobre el DTO: Livewire
 * enlaza arrays de forma nativa con `wire:model="form.data.experience.0.role"`,
 * mientras que un readonly DTO obligaría a implementar Wireable y a
 * reconstruir el objeto entero en cada tecla. El DTO se reconstruye recién en
 * los bordes — al renderizar el PDF y al guardar.
 *
 * @property array<string, mixed> $data
 */
class ResumeEditorForm extends Form
{
    /** @var array<string, mixed> */
    public array $data = [];

    public string $template = ResumeTemplate::Modern->value;

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'template' => ['required', 'string', 'in:'.implode(',', array_column(ResumeTemplate::cases(), 'value'))],

            'data.full_name' => ['required', 'string', 'max:120'],
            'data.headline' => ['nullable', 'string', 'max:160'],
            'data.summary' => ['nullable', 'string', 'max:800'],

            'data.contact.location' => ['nullable', 'string', 'max:120'],
            'data.contact.email' => ['nullable', 'email:rfc', 'max:160'],
            'data.contact.phone' => ['nullable', 'string', 'max:40'],
            'data.contact.links' => ['array', 'max:5'],
            'data.contact.links.*' => ['nullable', 'string', 'max:120'],

            'data.experience' => ['array', 'max:15'],
            'data.experience.*.role' => ['required', 'string', 'max:150'],
            'data.experience.*.company' => ['nullable', 'string', 'max:150'],
            'data.experience.*.location' => ['nullable', 'string', 'max:120'],
            'data.experience.*.dates' => ['nullable', 'string', 'max:60'],
            'data.experience.*.bullets' => ['array', 'max:8'],
            'data.experience.*.bullets.*' => ['nullable', 'string', 'max:400'],

            'data.education' => ['array', 'max:10'],
            'data.education.*.degree' => ['required', 'string', 'max:150'],
            'data.education.*.institution' => ['nullable', 'string', 'max:150'],
            'data.education.*.location' => ['nullable', 'string', 'max:120'],
            'data.education.*.dates' => ['nullable', 'string', 'max:60'],
            'data.education.*.description' => ['nullable', 'string', 'max:500'],

            'data.projects' => ['array', 'max:10'],
            'data.projects.*.name' => ['required', 'string', 'max:150'],
            'data.projects.*.description' => ['nullable', 'string', 'max:500'],
            'data.projects.*.meta' => ['nullable', 'string', 'max:150'],
            'data.projects.*.link' => ['nullable', 'string', 'max:120'],

            'data.skills' => ['array', 'max:10'],
            'data.skills.*.label' => ['required', 'string', 'max:40'],
            'data.skills.*.value' => ['required', 'string', 'max:400'],

            'data.certifications' => ['array', 'max:10'],
            'data.certifications.*.name' => ['required', 'string', 'max:180'],
            'data.certifications.*.issuer' => ['nullable', 'string', 'max:150'],
            'data.certifications.*.year' => ['nullable', 'string', 'max:20'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function validationAttributes(): array
    {
        return [
            'data.full_name' => 'nombre',
            'data.headline' => 'titular',
            'data.summary' => 'perfil',
            'data.contact.email' => 'correo',
            'data.experience.*.role' => 'cargo',
            'data.education.*.degree' => 'título',
            'data.projects.*.name' => 'nombre del proyecto',
            'data.skills.*.label' => 'categoría',
            'data.skills.*.value' => 'habilidades',
            'data.certifications.*.name' => 'certificación',
        ];
    }

    public function setResume(ResumeData $resume, ?ResumeTemplate $template = null): void
    {
        $this->data = $resume->toArray();
        $this->template = ($template ?? ResumeTemplate::Modern)->value;
    }

    public function toData(): ResumeData
    {
        return ResumeData::from(
            $this->data,
            Language::tryFrom($this->data['language'] ?? '') ?? Language::Spanish,
        );
    }

    public function templateEnum(): ResumeTemplate
    {
        return ResumeTemplate::from($this->template);
    }

    public function isEmpty(): bool
    {
        return $this->data === [];
    }

    /* ---------------------------------------------------------------------
     | Manipulación de secciones
     |
     | Toda escritura pasa por reindex(): borrar el elemento 1 de 3 deja el
     | array como [0 => …, 2 => …], que json_encode serializa como objeto en
     | vez de lista y rompe el `foreach` de la plantilla del PDF.
     |-------------------------------------------------------------------- */

    public function addEntry(string $section): void
    {
        $this->data[$section][] = match ($section) {
            'experience' => ['role' => '', 'company' => '', 'location' => '', 'dates' => '', 'bullets' => ['']],
            'education' => ['degree' => '', 'institution' => '', 'location' => '', 'dates' => '', 'description' => ''],
            'projects' => ['name' => '', 'description' => '', 'meta' => '', 'link' => ''],
            'skills' => ['label' => '', 'value' => ''],
            'certifications' => ['name' => '', 'issuer' => '', 'year' => ''],
            default => [],
        };
    }

    public function removeEntry(string $section, int $index): void
    {
        unset($this->data[$section][$index]);

        $this->reindex($section);
    }

    /**
     * Mueve una entrada una posición. El orden importa: un reclutador lee de
     * arriba hacia abajo y no llega al final.
     */
    public function moveEntry(string $section, int $index, int $offset): void
    {
        $entries = array_values($this->data[$section] ?? []);
        $target = $index + $offset;

        if (! isset($entries[$index], $entries[$target])) {
            return;
        }

        [$entries[$index], $entries[$target]] = [$entries[$target], $entries[$index]];

        $this->data[$section] = $entries;
    }

    public function addBullet(int $entryIndex): void
    {
        $this->data['experience'][$entryIndex]['bullets'][] = '';
    }

    public function removeBullet(int $entryIndex, int $bulletIndex): void
    {
        unset($this->data['experience'][$entryIndex]['bullets'][$bulletIndex]);

        $this->data['experience'][$entryIndex]['bullets'] = array_values(
            $this->data['experience'][$entryIndex]['bullets'] ?? []
        );
    }

    public function addLink(): void
    {
        $this->data['contact']['links'][] = '';
    }

    public function removeLink(int $index): void
    {
        unset($this->data['contact']['links'][$index]);

        $this->data['contact']['links'] = array_values($this->data['contact']['links'] ?? []);
    }

    private function reindex(string $section): void
    {
        $this->data[$section] = array_values($this->data[$section] ?? []);
    }
}
