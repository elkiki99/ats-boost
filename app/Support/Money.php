<?php

namespace App\Support;

class Money
{
    public static function format(float $amount, string $currency): string
    {
        return match ($currency) {
            'USD' => '$' . number_format($amount, 2),
            'ARS' => '$' . number_format($amount, 2, ',', '.'),
            'UYU' => '$' . number_format($amount, 2, ',', '.'),
            'BRL' => 'R$ ' . number_format($amount, 2, ',', '.'),
            'MXN' => '$' . number_format($amount, 2),
            default => number_format($amount, 2) . " {$currency}",
        };
    }
}