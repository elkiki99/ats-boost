<?php

use App\Enums\ResumeTemplate;
use App\Livewire\Documents\Edit;
use App\Models\Document;
use Livewire\Livewire;

beforeEach(function (): void {
    $this->user = subscribedUser();
    $this->document = Document::factory()->for($this->user)->create();
});

it('carga el documento estructurado en el formulario', function (): void {
    Livewire::actingAs($this->user)
        ->test(Edit::class, ['document' => $this->document])
        ->assertSet('resumeForm.data.full_name', 'Bruno Rossani')
        ->assertSet('resumeForm.template', ResumeTemplate::Modern->value)
        ->assertSet('resumeForm.data.experience.0.company', 'Multiline Contact Center')
        ->assertSet('resumeForm.data.skills.1.label', 'Frameworks');
});

it('guarda las ediciones sobre el mismo documento', function (): void {
    Livewire::actingAs($this->user)
        ->test(Edit::class, ['document' => $this->document])
        ->set('resumeForm.data.full_name', 'Bruno R. Rossani')
        ->set('resumeForm.data.experience.0.role', 'Desarrollador Senior')
        ->call('save')
        ->assertHasNoErrors();

    $resume = $this->document->refresh()->resume();

    expect($resume->fullName)->toBe('Bruno R. Rossani')
        ->and($resume->experience[0]->role)->toBe('Desarrollador Senior')
        // Se actualiza, no se duplica.
        ->and(Document::count())->toBe(1);
});

it('cambia la plantilla del documento', function (): void {
    Livewire::actingAs($this->user)
        ->test(Edit::class, ['document' => $this->document])
        ->set('resumeForm.template', ResumeTemplate::Compact->value)
        ->call('save');

    expect($this->document->refresh()->template)->toBe(ResumeTemplate::Compact);
});

it('agrega y quita viñetas sin dejar huecos en el array', function (): void {
    $component = Livewire::actingAs($this->user)
        ->test(Edit::class, ['document' => $this->document])
        ->call('addBullet', 0)
        ->set('resumeForm.data.experience.0.bullets.3', 'Un logro nuevo agregado a mano')
        ->call('removeBullet', 0, 1)
        ->call('save');

    $bullets = $this->document->refresh()->resume()->experience[0]->bullets;

    // array_is_list detecta el hueco que dejaría un unset sin reindexar: ese
    // hueco hace que json_encode serialice un objeto y rompa el PDF.
    expect(array_is_list($bullets))->toBeTrue()
        ->and($bullets)->toHaveCount(3)
        ->and($bullets)->toContain('Un logro nuevo agregado a mano');

    $component->assertHasNoErrors();
});

it('reordena los puestos', function (): void {
    $payload = $this->document->payload;
    $payload['experience'][] = ['role' => 'Pasante', 'company' => 'Otra', 'location' => null, 'dates' => null, 'bullets' => []];
    $this->document->update(['payload' => $payload]);

    Livewire::actingAs($this->user)
        ->test(Edit::class, ['document' => $this->document])
        ->call('moveEntry', 'experience', 1, -1)
        ->call('save');

    expect($this->document->refresh()->resume()->experience[0]->role)->toBe('Pasante');
});

it('elimina una entrada y reindexa la sección', function (): void {
    Livewire::actingAs($this->user)
        ->test(Edit::class, ['document' => $this->document])
        ->call('removeEntry', 'skills', 0)
        ->call('save');

    $payload = $this->document->refresh()->payload;

    expect($payload['skills'])->toHaveCount(2)
        ->and(array_is_list($payload['skills']))->toBeTrue();
});

it('no guarda un currículum sin nombre', function (): void {
    Livewire::actingAs($this->user)
        ->test(Edit::class, ['document' => $this->document])
        ->set('resumeForm.data.full_name', '')
        ->call('save')
        ->assertHasErrors(['resumeForm.data.full_name' => 'required']);
});

it('no guarda un correo inválido', function (): void {
    Livewire::actingAs($this->user)
        ->test(Edit::class, ['document' => $this->document])
        ->set('resumeForm.data.contact.email', 'no-es-un-correo')
        ->call('save')
        ->assertHasErrors(['resumeForm.data.contact.email' => 'email']);
});

it('edita los párrafos de una carta de presentación', function (): void {
    $letter = Document::factory()->for($this->user)->coverLetter()->create();

    Livewire::actingAs($this->user)
        ->test(Edit::class, ['document' => $letter])
        ->set('letterForm.data.paragraphs.0', 'Párrafo reescrito por el usuario.')
        ->call('removeParagraph', 2)
        ->call('save')
        ->assertHasNoErrors();

    $paragraphs = $letter->refresh()->coverLetter()->paragraphs;

    expect($paragraphs)->toHaveCount(2)
        ->and($paragraphs[0])->toBe('Párrafo reescrito por el usuario.');
});

it('no deja vaciar la carta por completo', function (): void {
    $letter = Document::factory()->for($this->user)->coverLetter()->create();

    Livewire::actingAs($this->user)
        ->test(Edit::class, ['document' => $letter])
        ->call('removeParagraph', 2)
        ->call('removeParagraph', 1)
        ->call('removeParagraph', 0)
        ->call('save')
        ->assertHasErrors('letterForm.data.paragraphs');
});
