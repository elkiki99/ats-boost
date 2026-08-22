<?php

namespace App\Models;

use App\Data\AtsReportData;
use App\Data\CoverLetterData;
use App\Data\ResumeData;
use App\Enums\DocumentType;
use App\Enums\Language;
use App\Enums\ResumeTemplate;
use Database\Factories\DocumentFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Un currículum adaptado, un currículum mejorado o una carta de presentación.
 *
 * Guarda el documento estructurado, nunca el PDF: el binario se regenera al
 * vuelo en cada descarga. Eso evita almacenar archivos con datos personales y
 * hace que un ajuste en la plantilla se refleje en el historial completo.
 *
 * @property DocumentType $type
 * @property Language $language
 * @property ResumeTemplate $template
 * @property array<string, mixed> $payload
 * @property array<string, mixed>|null $report
 */
class Document extends Model
{
    /** @use HasFactory<DocumentFactory> */
    use HasFactory;

    protected $fillable = [
        'type',
        'title',
        'language',
        'template',
        'payload',
        'report',
        'role',
        'company',
        'job_description',
        'source_filename',
        'ats_score',
    ];

    protected function casts(): array
    {
        return [
            'type' => DocumentType::class,
            'language' => Language::class,
            'template' => ResumeTemplate::class,
            'payload' => 'array',
            'report' => 'array',
            'ats_score' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @param  Builder<self>  $query
     * @return Builder<self>
     */
    public function scopeLatestFirst(Builder $query): Builder
    {
        return $query->orderByDesc('created_at')->orderByDesc('id');
    }

    /**
     * @param  Builder<self>  $query
     * @return Builder<self>
     */
    public function scopeOfType(Builder $query, DocumentType|string|null $type): Builder
    {
        return $query->when($type, fn (Builder $q) => $q->where(
            'type',
            $type instanceof DocumentType ? $type->value : $type,
        ));
    }

    public function resume(): ResumeData
    {
        return ResumeData::from($this->payload, $this->language);
    }

    public function coverLetter(): CoverLetterData
    {
        return CoverLetterData::from($this->payload, $this->language);
    }

    public function atsReport(): ?AtsReportData
    {
        return $this->report === null ? null : AtsReportData::from($this->report);
    }
}
