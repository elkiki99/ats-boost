<?php

namespace App\Data;

use Illuminate\Contracts\Support\Arrayable;

/**
 * Resultado del análisis ATS.
 *
 * El analizador anterior devolvía un entero suelto y nada más, así que el
 * usuario veía un número sin saber qué arreglar. Ahora el mismo llamado
 * devuelve también el desglose y los problemas concretos.
 *
 * @implements Arrayable<string, mixed>
 */
final readonly class AtsReportData implements Arrayable
{
    /**
     * @param  array<string, int>  $breakdown
     * @param  list<string>  $strengths
     * @param  list<array{title: string, detail: string, severity: string}>  $issues
     * @param  list<string>  $missingKeywords
     */
    public function __construct(
        public int $score,
        public array $breakdown = [],
        public array $strengths = [],
        public array $issues = [],
        public array $missingKeywords = [],
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function from(array $data): self
    {
        $breakdown = [];
        foreach (Cast::rows([$data['breakdown'] ?? []])[0] ?? [] as $key => $value) {
            $breakdown[(string) $key] = Cast::integer($value, 0, 100);
        }

        $issues = [];
        foreach (Cast::rows($data['issues'] ?? []) as $issue) {
            $title = Cast::string($issue['title'] ?? '');

            if ($title === '') {
                continue;
            }

            $severity = mb_strtolower(Cast::string($issue['severity'] ?? 'medium'));

            $issues[] = [
                'title' => $title,
                'detail' => Cast::string($issue['detail'] ?? ''),
                'severity' => in_array($severity, ['high', 'medium', 'low'], true) ? $severity : 'medium',
            ];
        }

        return new self(
            score: Cast::integer($data['score'] ?? 0, 0, 100),
            breakdown: $breakdown,
            strengths: Cast::stringList($data['strengths'] ?? []),
            issues: $issues,
            missingKeywords: Cast::stringList($data['missing_keywords'] ?? []),
        );
    }

    /**
     * Color semántico del indicador, alineado con las variantes de Flux.
     */
    public function tone(): string
    {
        return match (true) {
            $this->score >= 80 => 'green',
            $this->score >= 60 => 'amber',
            default => 'red',
        };
    }

    public function verdict(): string
    {
        return match (true) {
            $this->score >= 80 => 'Tu currículum está listo para pasar filtros ATS.',
            $this->score >= 60 => 'Tu currículum pasa, pero hay puntos que conviene corregir.',
            default => 'Tu currículum tiene problemas que un ATS penaliza.',
        };
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'score' => $this->score,
            'breakdown' => $this->breakdown,
            'strengths' => $this->strengths,
            'issues' => $this->issues,
            'missing_keywords' => $this->missingKeywords,
        ];
    }
}
