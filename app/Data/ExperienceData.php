<?php

namespace App\Data;

use Illuminate\Contracts\Support\Arrayable;

/**
 * @implements Arrayable<string, mixed>
 */
final readonly class ExperienceData implements Arrayable
{
    /**
     * @param  list<string>  $bullets
     */
    public function __construct(
        public string $role,
        public ?string $company = null,
        public ?string $location = null,
        public ?string $dates = null,
        public array $bullets = [],
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function from(array $data): self
    {
        return new self(
            role: Cast::string($data['role'] ?? ''),
            company: Cast::nullableString($data['company'] ?? null),
            location: Cast::nullableString($data['location'] ?? null),
            dates: Cast::nullableString($data['dates'] ?? null),
            bullets: Cast::stringList($data['bullets'] ?? []),
        );
    }

    /**
     * Una entrada sin cargo ni empresa es ruido que el modelo alucinó;
     * la plantilla la descarta antes de renderizar.
     */
    public function isEmpty(): bool
    {
        return blank($this->role) && blank($this->company);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'role' => $this->role,
            'company' => $this->company,
            'location' => $this->location,
            'dates' => $this->dates,
            'bullets' => $this->bullets,
        ];
    }
}
