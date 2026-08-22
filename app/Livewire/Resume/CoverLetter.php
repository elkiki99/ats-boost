<?php

namespace App\Livewire\Resume;

use App\Actions\Documents\StoreDocument;
use App\Actions\Resume\GenerateCoverLetter;
use App\Enums\DocumentType;
use App\Livewire\Concerns\HandlesGenerationFailures;
use App\Livewire\Forms\CoverLetterForm;
use Livewire\Component;
use Livewire\Features\SupportRedirects\Redirector;
use Livewire\WithFileUploads;

class CoverLetter extends Component
{
    use HandlesGenerationFailures, WithFileUploads;

    public CoverLetterForm $form;

    public function generate(GenerateCoverLetter $generate, StoreDocument $store): ?Redirector
    {
        $this->form->validate();

        $result = $this->attempt(fn () => $generate->handle(
            $this->form->resume,
            $this->form->description,
            $this->form->company,
        ));

        if ($result === null) {
            return null;
        }

        $document = $store->handle(
            user: auth()->user(),
            type: DocumentType::CoverLetter,
            data: $result['letter'],
            role: $result['job']->role,
            company: $this->form->company ?? $result['job']->company,
            jobDescription: $this->form->description,
            sourceFilename: $this->form->resume->getClientOriginalName(),
        );

        $this->succeeded('Tu carta está lista', 'Revisá el texto antes de descargarlo.');

        return $this->redirectRoute('documents.edit', $document, navigate: true);
    }

    public function render()
    {
        return view('livewire.resume.cover-letter')
            ->title('Carta de presentación • ATS Boost');
    }
}
