<?php

namespace App\Data;

use Illuminate\Contracts\Support\Arrayable;

/**
 * Una fila de la sección Habilidades: etiqueta a la izquierda ("Frameworks")
 * y lista separada por comas a la derecha.
 *
 * @implements Arrayable<string, mixed>
 */
final readonly class SkillGroupData implements Arrayable
{
    public function __construct(
        public string $label,
        public string $value,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function from(array $data): self
    {
        // El modelo a veces devuelve {"label": "Frameworks", "items": [...]}
        // en lugar de una cadena. Cast::string aplana la lista.
        $value = $data['value'] ?? $data['items'] ?? $data['skills'] ?? '';

        return new self(
            label: Cast::string($data['label'] ?? $data['category'] ?? ''),
            value: is_array($value)
                ? implode(', ', Cast::stringList($value))
                : Cast::string($value),
        );
    }

    public function isEmpty(): bool
    {
        return blank($this->value);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'label' => $this->label,
            'value' => $this->value,
        ];
    }
}
