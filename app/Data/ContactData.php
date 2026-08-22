<?php

namespace App\Data;

use Illuminate\Contracts\Support\Arrayable;

/**
 * @implements Arrayable<string, mixed>
 */
final readonly class ContactData implements Arrayable
{
    /**
     * @param  list<string>  $links
     */
    public function __construct(
        public ?string $location = null,
        public ?string $email = null,
        public ?string $phone = null,
        public array $links = [],
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function from(array $data): self
    {
        return new self(
            location: Cast::nullableString($data['location'] ?? null),
            email: Cast::nullableString($data['email'] ?? null),
            phone: Cast::nullableString($data['phone'] ?? null),
            links: Cast::stringList($data['links'] ?? []),
        );
    }

    /**
     * La línea de contacto del encabezado: todo lo que exista, separado por
     * un punto medio, sin separadores huérfanos cuando falta un dato.
     */
    public function line(string $separator = ' · '): string
    {
        return implode($separator, array_filter([
            $this->location,
            $this->email,
            $this->phone,
            ...$this->links,
        ], fn (?string $part): bool => filled($part)));
    }

    public function isEmpty(): bool
    {
        return $this->line() === '';
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'location' => $this->location,
            'email' => $this->email,
            'phone' => $this->phone,
            'links' => $this->links,
        ];
    }
}
