<?php

namespace App\Data;

use App\Enums\Language;
use Illuminate\Contracts\Support\Arrayable;

/**
 * Lectura estructurada de la oferta de trabajo.
 *
 * Reemplaza tres llamadas separadas del flujo anterior (extraer requisitos,
 * extraer rol, extraer empresa) por una sola.
 *
 * @implements Arrayable<string, mixed>
 */
final readonly class JobPostingData implements Arrayable
{
    /**
     * @param  list<string>  $keywords
     * @param  list<string>  $requirements
     * @param  list<string>  $responsibilities
     */
    public function __construct(
        public ?string $role = null,
        public ?string $company = null,
        public ?string $seniority = null,
        public array $keywords = [],
        public array $requirements = [],
        public array $responsibilities = [],
        public Language $language = Language::Spanish,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function from(array $data, ?Language $language = null): self
    {
        return new self(
            role: Cast::nullableString($data['role'] ?? null),
            company: Cast::nullableString($data['company'] ?? null),
            seniority: Cast::nullableString($data['seniority'] ?? null),
            keywords: Cast::stringList($data['keywords'] ?? []),
            requirements: Cast::stringList($data['requirements'] ?? []),
            responsibilities: Cast::stringList($data['responsibilities'] ?? []),
            language: $language ?? Language::tryFrom(Cast::string($data['language'] ?? '')) ?? Language::Spanish,
        );
    }

    /**
     * Bloque compacto que se inyecta en el prompt de adaptación.
     */
    public function toPromptContext(): string
    {
        $section = static fn (string $title, array $items): string => $items === []
            ? ''
            : "{$title}:\n- ".implode("\n- ", $items)."\n";

        return trim(implode("\n", array_filter([
            $this->role ? "Puesto: {$this->role}" : null,
            $this->seniority ? "Nivel: {$this->seniority}" : null,
            $section('Palabras clave', $this->keywords),
            $section('Requisitos', $this->requirements),
            $section('Responsabilidades', $this->responsibilities),
        ])));
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'role' => $this->role,
            'company' => $this->company,
            'seniority' => $this->seniority,
            'keywords' => $this->keywords,
            'requirements' => $this->requirements,
            'responsibilities' => $this->responsibilities,
            'language' => $this->language->value,
        ];
    }
}
