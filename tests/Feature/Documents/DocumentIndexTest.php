<?php

use App\Enums\DocumentType;
use App\Livewire\Documents\Index;
use App\Models\Document;
use Livewire\Livewire;

beforeEach(function (): void {
    $this->user = subscribedUser();
});

it('lista solo los documentos del usuario', function (): void {
    Document::factory()->for($this->user)->create(['title' => 'Backend en Acme']);
    Document::factory()->for(subscribedUser())->create(['title' => 'De otra persona']);

    Livewire::actingAs($this->user)
        ->test(Index::class)
        ->assertSee('Backend en Acme')
        ->assertDontSee('De otra persona');
});

it('los ordena del más nuevo al más viejo', function (): void {
    Document::factory()->for($this->user)->create(['title' => 'Viejo', 'created_at' => now()->subWeek()]);
    Document::factory()->for($this->user)->create(['title' => 'Nuevo', 'created_at' => now()]);

    Livewire::actingAs($this->user)
        ->test(Index::class)
        ->assertSeeInOrder(['Nuevo', 'Viejo']);
});

it('filtra por tipo', function (): void {
    Document::factory()->for($this->user)->create(['title' => 'Un CV adaptado']);
    Document::factory()->for($this->user)->coverLetter()->create(['title' => 'Una carta']);

    Livewire::actingAs($this->user)
        ->test(Index::class)
        ->set('type', DocumentType::CoverLetter->value)
        ->assertSee('Una carta')
        ->assertDontSee('Un CV adaptado');
});

it('busca por puesto y por empresa', function (): void {
    Document::factory()->for($this->user)->create(['title' => 'Uno', 'company' => 'Multiline']);
    Document::factory()->for($this->user)->create(['title' => 'Dos', 'company' => 'Otra Empresa']);

    Livewire::actingAs($this->user)
        ->test(Index::class)
        ->set('search', 'Multiline')
        ->assertSee('Uno')
        ->assertDontSee('Dos');
});

it('borra un documento propio', function (): void {
    $document = Document::factory()->for($this->user)->create();

    Livewire::actingAs($this->user)
        ->test(Index::class)
        ->call('delete', $document)
        ->assertHasNoErrors();

    expect(Document::count())->toBe(0);
});

it('no borra el documento de otra persona', function (): void {
    $document = Document::factory()->for(subscribedUser())->create();

    Livewire::actingAs($this->user)
        ->test(Index::class)
        ->call('delete', $document)
        ->assertForbidden();

    expect(Document::count())->toBe(1);
});

it('muestra el estado vacío cuando no hay nada generado', function (): void {
    Livewire::actingAs($this->user)
        ->test(Index::class)
        ->assertSee('Todavía no generaste ningún documento');
});
