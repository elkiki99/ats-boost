<?php

namespace App\Enums;

enum DocumentType: string
{
    case TailoredResume = 'tailored_resume';
    case ImprovedResume = 'improved_resume';
    case CoverLetter = 'cover_letter';

    public function label(): string
    {
        return match ($this) {
            self::TailoredResume => 'Currículum adaptado',
            self::ImprovedResume => 'Currículum mejorado',
            self::CoverLetter => 'Carta de presentación',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::TailoredResume => 'sparkles',
            self::ImprovedResume => 'arrow-trending-up',
            self::CoverLetter => 'envelope',
        };
    }

    public function isResume(): bool
    {
        return $this !== self::CoverLetter;
    }
}
