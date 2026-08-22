<?php

namespace App\Data;

use App\Enums\Language;
use Illuminate\Contracts\Support\Arrayable;

/**
 * Representación estructurada de un currículum.
 *
 * Es la única moneda de cambio del dominio: las acciones de IA la producen y
 * la consumen, el editor de Livewire la edita como array y las plantillas de
 * PDF la renderizan. Ninguna capa vuelve a tocar HTML generado por el modelo.
 *
 * @implements Arrayable<string, mixed>
 */
final readonly class ResumeData implements Arrayable
{
    /**
     * @param  list<ExperienceData>  $experience
     * @param  list<EducationData>  $education
     * @param  list<ProjectData>  $projects
     * @param  list<SkillGroupData>  $skills
     * @param  list<CertificationData>  $certifications
     */
    public function __construct(
        public string $fullName,
        public ?string $headline,
        public ContactData $contact,
        public ?string $summary = null,
        public array $experience = [],
        public array $education = [],
        public array $projects = [],
        public array $skills = [],
        public array $certifications = [],
        public Language $language = Language::Spanish,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function from(array $data, ?Language $language = null): self
    {
        $map = static fn (string $key, string $class): array => array_values(array_filter(
            array_map([$class, 'from'], Cast::rows($data[$key] ?? [])),
            fn (object $item): bool => ! $item->isEmpty(),
        ));

        return new self(
            fullName: Cast::string($data['full_name'] ?? $data['fullName'] ?? ''),
            headline: Cast::nullableString($data['headline'] ?? null),
            contact: ContactData::from(Cast::rows([$data['contact'] ?? []])[0] ?? []),
            summary: Cast::nullableString($data['summary'] ?? null),
            experience: $map('experience', ExperienceData::class),
            education: $map('education', EducationData::class),
            projects: $map('projects', ProjectData::class),
            skills: $map('skills', SkillGroupData::class),
            certifications: $map('certifications', CertificationData::class),
            language: $language
                ?? Language::tryFrom(Cast::string($data['language'] ?? ''))
                ?? Language::Spanish,
        );
    }

    /**
     * Un CV vacío significa que la extracción falló: el PDF no debe generarse.
     */
    public function isEmpty(): bool
    {
        return $this->experience === []
            && $this->education === []
            && $this->projects === []
            && $this->skills === [];
    }

    /**
     * Nombre visible, con marcador de posición cuando el CV original no lo
     * traía (pasa con PDFs donde el nombre está dentro de una imagen).
     */
    public function displayName(): string
    {
        return $this->fullName !== ''
            ? $this->fullName
            : $this->language->pick('Insertar nombre aquí', 'Insert name here');
    }

    public function displayContactLine(): string
    {
        if (! $this->contact->isEmpty()) {
            return $this->contact->line();
        }

        return $this->language->pick(
            'Insertar ciudad, país · Insertar correo · Insertar teléfono',
            'Insert city, country · Insert email · Insert phone',
        );
    }

    /**
     * Texto plano del CV, usado para volver a puntuarlo o para alimentar la
     * carta de presentación sin re-parsear el PDF original.
     */
    public function toPlainText(): string
    {
        $lines = [$this->displayName(), $this->headline, $this->contact->line(), '', $this->summary, ''];

        foreach ($this->experience as $entry) {
            $lines[] = trim("{$entry->role}, {$entry->company}");
            $lines[] = trim("{$entry->location} {$entry->dates}");
            foreach ($entry->bullets as $bullet) {
                $lines[] = "- {$bullet}";
            }
            $lines[] = '';
        }

        foreach ($this->education as $entry) {
            $lines[] = trim("{$entry->degree}, {$entry->institution}");
            $lines[] = trim("{$entry->location} {$entry->dates}");
            $lines[] = $entry->description;
            $lines[] = '';
        }

        foreach ($this->projects as $project) {
            $lines[] = trim("{$project->name} — {$project->description} {$project->meta}");
        }

        foreach ($this->skills as $group) {
            $lines[] = "{$group->label}: {$group->value}";
        }

        foreach ($this->certifications as $certification) {
            $lines[] = $certification->line();
        }

        return trim(implode("\n", array_filter($lines, fn (?string $line): bool => $line !== null)));
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $list = static fn (array $items): array => array_map(
            fn (Arrayable $item): array => $item->toArray(),
            $items,
        );

        return [
            'full_name' => $this->fullName,
            'headline' => $this->headline,
            'contact' => $this->contact->toArray(),
            'summary' => $this->summary,
            'experience' => $list($this->experience),
            'education' => $list($this->education),
            'projects' => $list($this->projects),
            'skills' => $list($this->skills),
            'certifications' => $list($this->certifications),
            'language' => $this->language->value,
        ];
    }
}
