<?php

namespace App\Actions\Resume;

use App\Enums\DocumentType;
use Illuminate\Support\Str;

/**
 * Arma el nombre del archivo descargado.
 *
 * Importa más de lo que parece: el reclutador ve este texto antes que el
 * contenido, y un "CV (3).pdf" en la bandeja compite mal contra
 * "Bruno Rossani - Desarrollador de Software.pdf".
 */
class BuildDocumentFileName
{
    public function handle(
        DocumentType $type,
        ?string $candidateName,
        ?string $role = null,
        ?string $company = null,
    ): string {
        $parts = array_filter([
            $this->clean($candidateName) ?: $type->label(),
            $this->clean($role),
            $this->clean($company),
        ]);

        return implode(' - ', $parts).'.pdf';
    }

    /**
     * Quita lo que rompe un nombre de archivo en Windows, macOS o Linux, más
     * los puntos y espacios finales que Windows recorta en silencio.
     */
    private function clean(?string $value): string
    {
        if (blank($value)) {
            return '';
        }

        $value = preg_replace('/[\/\\\\:*?"<>|\x00-\x1F]+/u', ' ', $value) ?? '';
        $value = trim(preg_replace('/\s+/u', ' ', $value) ?? '', " .\t\n\r\0\x0B");

        return Str::limit($value, 60, '');
    }
}
