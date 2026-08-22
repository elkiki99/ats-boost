<?php

namespace App\Livewire\Forms;

use Illuminate\Http\UploadedFile;
use Livewire\Form;

/**
 * Base de los formularios que reciben un archivo de currículum.
 *
 * En Livewire el equivalente a una FormRequest es un objeto Form: saca la
 * validación del componente, se puede testear suelto y se reutiliza entre las
 * tres pantallas. Antes las mismas reglas estaban repetidas como atributos
 * #[Validate] en cuatro componentes distintos, ya con divergencias.
 */
abstract class ResumeUploadForm extends Form
{
    public ?UploadedFile $resume = null;

    /**
     * @return array<string, mixed>
     */
    protected function resumeRules(): array
    {
        return [
            'resume' => [
                'required',
                'file',
                'mimes:pdf,txt',
                // mimetypes valida el contenido real, no la extensión: sin
                // esto alcanza con renombrar cualquier archivo a .pdf.
                'mimetypes:application/pdf,text/plain',
                'max:'.config('resume.limits.upload_kilobytes'),
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function resumeAttributes(): array
    {
        return ['resume' => 'currículum'];
    }

    /**
     * @return array<string, string>
     */
    protected function resumeMessages(): array
    {
        return [
            'resume.required' => 'Subí tu currículum para continuar.',
            'resume.mimes' => 'El archivo tiene que ser un PDF o un TXT.',
            'resume.mimetypes' => 'El archivo no parece un PDF válido. Exportalo de nuevo desde el editor original.',
            'resume.max' => 'El archivo supera el límite de :max KB.',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function descriptionRules(): array
    {
        return [
            'description' => [
                'required',
                'string',
                'min:80',
                'max:'.config('resume.limits.job_description_characters'),
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function descriptionMessages(): array
    {
        return [
            'description.required' => 'Pegá la descripción del puesto.',
            // El mínimo existe porque con un par de líneas el modelo no tiene
            // de dónde sacar requisitos y devuelve una adaptación genérica.
            'description.min' => 'La descripción es demasiado corta para adaptar el CV. Pegá el aviso completo (al menos :min caracteres).',
            'description.max' => 'La descripción supera los :max caracteres. Recortá las partes que no describen el puesto.',
        ];
    }
}
