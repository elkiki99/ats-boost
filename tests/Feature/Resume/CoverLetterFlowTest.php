<?php

use App\Enums\DocumentType;
use App\Livewire\Resume\CoverLetter;
use App\Models\Document;
use Database\Factories\DocumentFactory;
use Livewire\Livewire;

beforeEach(function (): void {
    $this->user = subscribedUser();
});

it('genera una carta estructurada y la guarda', function (): void {
    fakeChatResponses(
        fakeJobPosting(),
        DocumentFactory::resumePayload(),
        DocumentFactory::letterPayload(),
    );

    Livewire::actingAs($this->user)
        ->test(CoverLetter::class)
        ->set('form.resume', fakeResumeUpload())
        ->set('form.description', fakeJobDescription())
        ->set('form.company', 'Acme S.A.')
        ->call('generate')
        ->assertHasNoErrors()
        ->assertRedirect();

    $document = Document::sole();
    $letter = $document->coverLetter();

    expect($document->type)->toBe(DocumentType::CoverLetter)
        ->and($document->company)->toBe('Acme S.A.')
        ->and($letter->paragraphs)->toHaveCount(3)
        // La empresa que escribió el usuario gana sobre la que infirió el modelo.
        ->and($letter->company)->toBe('Acme S.A.');
});

it('toma el nombre y el contacto del CV, no de lo que invente el modelo', function (): void {
    $letterPayload = DocumentFactory::letterPayload();
    $letterPayload['candidate_name'] = 'Nombre Equivocado';
    $letterPayload['contact']['email'] = 'inventado@ejemplo.com';

    fakeChatResponses(fakeJobPosting(), DocumentFactory::resumePayload(), $letterPayload);

    Livewire::actingAs($this->user)
        ->test(CoverLetter::class)
        ->set('form.resume', fakeResumeUpload())
        ->set('form.description', fakeJobDescription())
        ->call('generate');

    $letter = Document::sole()->coverLetter();

    expect($letter->candidateName)->toBe('Bruno Rossani')
        ->and($letter->contact->email)->toBe('brossani23@gmail.com');
});

it('deja la empresa vacía cuando no se informa ninguna', function (): void {
    $posting = fakeJobPosting();
    $posting['company'] = null;

    $letterPayload = DocumentFactory::letterPayload();
    $letterPayload['company'] = null;

    fakeChatResponses($posting, DocumentFactory::resumePayload(), $letterPayload);

    Livewire::actingAs($this->user)
        ->test(CoverLetter::class)
        ->set('form.resume', fakeResumeUpload())
        ->set('form.description', fakeJobDescription())
        ->call('generate');

    expect(Document::sole()->company)->toBeNull();
});
