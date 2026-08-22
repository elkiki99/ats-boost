<?php

namespace App\Data;

use Illuminate\Contracts\Support\Arrayable;

/**
 * @implements Arrayable<string, mixed>
 */
final readonly class CertificationData implements Arrayable
{
    public function __construct(
        public string $name,
        public ?string $issuer = null,
        public ?string $year = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function from(array $data): self
    {
        return new self(
            name: Cast::string($data['name'] ?? ''),
            issuer: Cast::nullableString($data['issuer'] ?? null),
            year: Cast::nullableString($data['year'] ?? null),
        );
    }

    public function isEmpty(): bool
    {
        return blank($this->name);
    }

    /**
     * "CS50's Introduction to Computer Science — Harvard Online"
     */
    public function line(): string
    {
        return implode(' — ', array_filter([$this->name, $this->issuer], fn (?string $p): bool => filled($p)));
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'issuer' => $this->issuer,
            'year' => $this->year,
        ];
    }
}
