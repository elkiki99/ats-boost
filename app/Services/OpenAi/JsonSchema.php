<?php

namespace App\Services\OpenAi;

/**
 * Un esquema con nombre, listo para el modo `strict` de OpenAI.
 *
 * `strict` obliga a que cada objeto declare `additionalProperties: false` y
 * liste **todas** sus propiedades en `required`; los campos opcionales se
 * expresan permitiendo `null`. Los helpers de esta clase construyen los nodos
 * ya conformes para no repetir esa disciplina en cada esquema.
 */
final readonly class JsonSchema
{
    /**
     * @param  array<string, mixed>  $schema
     */
    private function __construct(
        public string $name,
        public array $schema,
    ) {}

    /**
     * @param  array<string, array<string, mixed>>  $properties
     */
    public static function object(string $name, array $properties): self
    {
        return new self($name, self::shape($properties));
    }

    /**
     * Objeto anidado con todas sus claves obligatorias.
     *
     * @param  array<string, array<string, mixed>>  $properties
     * @return array<string, mixed>
     */
    public static function shape(array $properties, ?string $description = null): array
    {
        return array_filter([
            'type' => 'object',
            'description' => $description,
            'properties' => $properties,
            'required' => array_keys($properties),
            'additionalProperties' => false,
        ], fn (mixed $value): bool => $value !== null);
    }

    /**
     * Cadena obligatoria.
     *
     * @return array<string, mixed>
     */
    public static function string(string $description): array
    {
        return ['type' => 'string', 'description' => $description];
    }

    /**
     * Cadena que el modelo puede dejar en null cuando el dato no está en el
     * CV. Es la forma de tener campos opcionales bajo `strict`.
     *
     * @return array<string, mixed>
     */
    public static function nullableString(string $description): array
    {
        return [
            'type' => ['string', 'null'],
            'description' => $description.' Devolvé null si el dato no aparece en la fuente; no lo inventes.',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function integer(string $description, int $minimum = 0, int $maximum = 100): array
    {
        return [
            'type' => 'integer',
            'description' => $description,
            'minimum' => $minimum,
            'maximum' => $maximum,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function enum(string $description, array $values): array
    {
        return ['type' => 'string', 'description' => $description, 'enum' => $values];
    }

    /**
     * @param  array<string, mixed>  $items
     * @return array<string, mixed>
     */
    public static function array(string $description, array $items): array
    {
        return ['type' => 'array', 'description' => $description, 'items' => $items];
    }

    /**
     * @return array<string, mixed>
     */
    public static function stringArray(string $description): array
    {
        return self::array($description, ['type' => 'string']);
    }

    /**
     * Payload tal como lo espera el parámetro `response_format` del endpoint
     * de chat completions.
     *
     * @return array<string, mixed>
     */
    public function toResponseFormat(): array
    {
        return [
            'type' => 'json_schema',
            'json_schema' => [
                'name' => $this->name,
                'strict' => true,
                'schema' => $this->schema,
            ],
        ];
    }
}
