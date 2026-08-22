<?php

namespace App\Livewire\Resume;

use App\Actions\Documents\StoreDocument;
use App\Actions\Resume\GenerateTailoredResume;
use App\Enums\DocumentType;
use App\Livewire\Concerns\HandlesGenerationFailures;
use App\Livewire\Forms\TailorResumeForm;
use Livewire\Component;
use Livewire\Features\SupportRedirects\Redirector;
use Livewire\WithFileUploads;

class Tailor extends Component
{
    use HandlesGenerationFailures, WithFileUploads;

    public TailorResumeForm $form;

    public function generate(GenerateTailoredResume $generate, StoreDocument $store): ?Redirector
    {
        $this->form->validate();

        $result = $this->attempt(fn () => $generate->handle(
            $this->form->resume,
            $this->form->description,
        ));

        if ($result === null) {
            return null;
        }

        $document = $store->handle(
            user: auth()->user(),
            type: DocumentType::TailoredResume,
            data: $result['resume'],
            role: $result['job']->role,
            company: $result['job']->company,
            jobDescription: $this->form->description,
            sourceFilename: $this->form->resume->getClientOriginalName(),
        );

        $this->succeeded(
            'Tu currículum está listo',
            'Revisalo y ajustá lo que quieras antes de descargarlo.',
        );

        // Se redirige al editor en vez de abrir un modal: el resultado ya está
        // guardado, así que tiene URL propia y el usuario puede volver a él.
        return $this->redirectRoute('documents.edit', $document, navigate: true);
    }

    public function render()
    {
        return view('livewire.resume.tailor')
            ->title('Adaptar currículum • ATS Boost');
    }
}
