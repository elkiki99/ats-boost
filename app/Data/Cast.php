<?php

namespace App\Data;

/**
 * Normalización de los valores que llegan del modelo, de la base de datos o
 * del editor de Livewire.
 *
 * Los tres orígenes mienten de formas distintas: el modelo devuelve `"null"`
 * o cadenas vacías, la base devuelve JSON con claves ausentes y el editor
 * devuelve arrays con huecos tras borrar un elemento. Todo pasa por acá.
 *
 * @internal
 */
final class Cast
{
    public static function nullableString(mixed $value): ?string
    {
        if (is_array($value)) {
            $value = implode(' ', array_filter($value, 'is_scalar'));
        }

        if (! is_scalar($value)) {
            return null;
        }

        $value = trim((string) $value);

        // El modelo devuelve estos literales cuando no encuentra el dato.
        if (in_array(mb_strtolower($value), ['', 'null', 'n/a', 'na', 'none', 'unknown', 'desconocido'], true)) {
            return null;
        }

        return $value;
    }

    public static function string(mixed $value, string $default = ''): string
    {
        return self::nullableString($value) ?? $default;
    }

    /**
     * @return list<string>
     */
    public static function stringList(mixed $value): array
    {
        if (is_string($value)) {
            $value = [$value];
        }

        if (! is_array($value)) {
            return [];
        }

        $items = array_map(self::nullableString(...), $value);

        // array_values reindexa: sin esto, borrar la viñeta 1 de 3 deja el
        // array como [0 => …, 2 => …] y json_encode lo serializa como objeto.
        return array_values(array_filter($items, fn (?string $item): bool => $item !== null));
    }

    /**
     * @return list<array<string, mixed>>
     */
    public static function rows(mixed $value): array
    {
        if (! is_array($value)) {
            return [];
        }

        return array_values(array_filter($value, is_array(...)));
    }

    public static function integer(mixed $value, int $min, int $max, int $default = 0): int
    {
        if (! is_numeric($value)) {
            return $default;
        }

        return (int) max($min, min($max, (int) round((float) $value)));
    }
}
