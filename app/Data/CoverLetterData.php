<?php

namespace App\Data;

use App\Enums\Language;
use Illuminate\Contracts\Support\Arrayable;

/**
 * @implements Arrayable<string, mixed>
 */
final readonly class CoverLetterData implements Arrayable
{
    /**
     * @param  list<string>  $paragraphs
     */
    public function __construct(
        public string $candidateName,
        public ContactData $contact,
        public ?string $role = null,
        public ?string $company = null,
        public ?string $greeting = null,
        public array $paragraphs = [],
        public ?string $closing = null,
        public Language $language = Language::Spanish,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function from(array $data, ?Language $language = null): self
    {
        return new self(
            candidateName: Cast::string($data['candidate_name'] ?? ''),
            contact: ContactData::from(Cast::rows([$data['contact'] ?? []])[0] ?? []),
            role: Cast::nullableString($data['role'] ?? null),
            company: Cast::nullableString($data['company'] ?? null),
            greeting: Cast::nullableString($data['greeting'] ?? null),
            paragraphs: Cast::stringList($data['paragraphs'] ?? []),
            closing: Cast::nullableString($data['closing'] ?? null),
            language: $language ?? Language::tryFrom(Cast::string($data['language'] ?? '')) ?? Language::Spanish,
        );
    }

    public function isEmpty(): bool
    {
        return $this->paragraphs === [];
    }

    public function displayName(): string
    {
        return $this->candidateName !== ''
            ? $this->candidateName
            : $this->language->pick('Insertar nombre aquí', 'Insert name here');
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'candidate_name' => $this->candidateName,
            'contact' => $this->contact->toArray(),
            'role' => $this->role,
            'company' => $this->company,
            'greeting' => $this->greeting,
            'paragraphs' => $this->paragraphs,
            'closing' => $this->closing,
            'language' => $this->language->value,
        ];
    }
}
