<?php

namespace App\Data;

use Illuminate\Contracts\Support\Arrayable;

/**
 * @implements Arrayable<string, mixed>
 */
final readonly class ProjectData implements Arrayable
{
    public function __construct(
        public string $name,
        public ?string $description = null,
        public ?string $meta = null,
        public ?string $link = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function from(array $data): self
    {
        return new self(
            name: Cast::string($data['name'] ?? ''),
            description: Cast::nullableString($data['description'] ?? null),
            meta: Cast::nullableString($data['meta'] ?? null),
            link: Cast::nullableString($data['link'] ?? null),
        );
    }

    public function isEmpty(): bool
    {
        return blank($this->name) && blank($this->description);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'meta' => $this->meta,
            'link' => $this->link,
        ];
    }
}
