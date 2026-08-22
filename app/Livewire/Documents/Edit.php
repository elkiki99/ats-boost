<?php

namespace App\Livewire\Documents;

use App\Enums\DocumentType;
use App\Enums\ResumeTemplate;
use App\Livewire\Concerns\HandlesGenerationFailures;
use App\Livewire\Forms\CoverLetterEditorForm;
use App\Livewire\Forms\ResumeEditorForm;
use App\Models\Document;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Locked;
use Livewire\Component;

/**
 * Editor del documento generado.
 *
 * Reemplaza al editor de texto enriquecido: el usuario ya no toca HTML, edita
 * campos concretos. Eso es lo que permite que la plantilla del PDF garantice
 * el diseño — con HTML libre, una edición del usuario podía romper el
 * formato entero y no había forma de repararlo.
 */
class Edit extends Component
{
    use HandlesGenerationFailures;

    #[Locked]
    public Document $document;

    public ResumeEditorForm $resumeForm;

    public CoverLetterEditorForm $letterForm;

    /**
     * Cambia en cada guardado para forzar la recarga del iframe: sin esto el
     * navegador sirve el PDF cacheado y el usuario cree que no se guardó.
     */
    public int $previewVersion = 0;

    public function mount(Document $document): void
    {
        Gate::authorize('update', $document);

        $this->document = $document;

        if ($document->type->isResume()) {
            $this->resumeForm->setResume($document->resume(), $document->template);
        } else {
            $this->letterForm->setLetter($document->coverLetter());
        }
    }

    public function isResume(): bool
    {
        return $this->document->type->isResume();
    }

    public function save(): void
    {
        Gate::authorize('update', $this->document);

        if ($this->isResume()) {
            $this->resumeForm->validate();

            $this->document->update([
                'payload' => $this->resumeForm->toData()->toArray(),
                'template' => $this->resumeForm->templateEnum(),
            ]);
        } else {
            $this->letterForm->validate();

            $this->document->update([
                'payload' => $this->letterForm->toData()->toArray(),
            ]);
        }

        $this->previewVersion++;

        $this->succeeded('Cambios guardados', 'La vista previa ya refleja tu última edición.');
    }

    /**
     * Guarda y deja que el navegador siga al enlace de descarga.
     *
     * La descarga es una ruta HTTP propia y no un streamDownload desde
     * Livewire: así tiene URL estable, pasa por la política del documento y se
     * puede volver a abrir desde el historial.
     */
    public function saveAndDownload(): void
    {
        $this->save();

        $this->dispatch('download-ready', url: route('documents.download', $this->document));
    }

    /* ---------------------------------------------------------------------
     | Manipulación de secciones
     |
     | Livewire solo expone al frontend los métodos públicos del componente:
     | `wire:click="resumeForm.addBullet(0)"` no llega al objeto Form. Estos
     | delegados son la superficie que ve la vista; la lógica sigue viviendo
     | en el Form, que es donde también corre la validación.
     |-------------------------------------------------------------------- */

    public function addEntry(string $section): void
    {
        $this->resumeForm->addEntry($section);
    }

    public function removeEntry(string $section, int $index): void
    {
        $this->resumeForm->removeEntry($section, $index);
    }

    public function moveEntry(string $section, int $index, int $offset): void
    {
        $this->resumeForm->moveEntry($section, $index, $offset);
    }

    public function addBullet(int $entryIndex): void
    {
        $this->resumeForm->addBullet($entryIndex);
    }

    public function removeBullet(int $entryIndex, int $bulletIndex): void
    {
        $this->resumeForm->removeBullet($entryIndex, $bulletIndex);
    }

    public function addLink(): void
    {
        $this->resumeForm->addLink();
    }

    public function removeLink(int $index): void
    {
        $this->resumeForm->removeLink($index);
    }

    public function addParagraph(): void
    {
        $this->letterForm->addParagraph();
    }

    public function removeParagraph(int $index): void
    {
        $this->letterForm->removeParagraph($index);
    }

    public function moveParagraph(int $index, int $offset): void
    {
        $this->letterForm->moveParagraph($index, $offset);
    }

    /**
     * @return array<string, string>
     */
    public function templateOptions(): array
    {
        $options = [];

        foreach (ResumeTemplate::cases() as $template) {
            $options[$template->value] = $template->label();
        }

        return $options;
    }

    public function render()
    {
        return view('livewire.documents.edit')
            ->title($this->document->title.' • ATS Boost');
    }

    public function documentType(): DocumentType
    {
        return $this->document->type;
    }
}
