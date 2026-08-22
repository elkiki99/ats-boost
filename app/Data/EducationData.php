<?php

namespace App\Data;

use Illuminate\Contracts\Support\Arrayable;

/**
 * @implements Arrayable<string, mixed>
 */
final readonly class EducationData implements Arrayable
{
    public function __construct(
        public string $degree,
        public ?string $institution = null,
        public ?string $location = null,
        public ?string $dates = null,
        public ?string $description = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function from(array $data): self
    {
        return new self(
            degree: Cast::string($data['degree'] ?? ''),
            institution: Cast::nullableString($data['institution'] ?? null),
            location: Cast::nullableString($data['location'] ?? null),
            dates: Cast::nullableString($data['dates'] ?? null),
            description: Cast::nullableString($data['description'] ?? null),
        );
    }

    public function isEmpty(): bool
    {
        return blank($this->degree) && blank($this->institution);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'degree' => $this->degree,
            'institution' => $this->institution,
            'location' => $this->location,
            'dates' => $this->dates,
            'description' => $this->description,
        ];
    }
}
