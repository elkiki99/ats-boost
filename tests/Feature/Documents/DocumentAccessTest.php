<?php

use App\Livewire\Documents\Edit;
use App\Models\Document;
use App\Models\Subscriber;
use App\Models\User;
use Database\Factories\DocumentFactory;
use Livewire\Livewire;

it('descarga el PDF del documento propio', function (): void {
    $user = subscribedUser();
    $document = Document::factory()->for($user)->create();

    $response = $this->actingAs($user)->get(route('documents.download', $document));

    $response->assertOk()
        ->assertHeader('content-type', 'application/pdf');

    expect($response->getContent())->toStartWith('%PDF-');
});

it('pone el nombre del candidato en la cabecera de descarga', function (): void {
    $user = subscribedUser();
    $document = Document::factory()->for($user)->create([
        'role' => 'Desarrollador de Software',
        'company' => null,
    ]);

    $this->actingAs($user)
        ->get(route('documents.download', $document))
        ->assertHeader(
            'content-disposition',
            'attachment; filename="Bruno Rossani - Desarrollador de Software.pdf"',
        );
});

it('codifica los acentos del nombre en la cabecera en vez de romperlos', function (): void {
    $user = subscribedUser();

    $payload = DocumentFactory::resumePayload();
    $payload['full_name'] = 'Martín Núñez';

    $document = Document::factory()->for($user)->create([
        'payload' => $payload,
        'role' => null,
        'company' => null,
    ]);

    $disposition = $this->actingAs($user)
        ->get(route('documents.download', $document))
        ->headers->get('content-disposition');

    // RFC 6266: el nombre real viaja en filename*, y filename queda con una
    // versión ASCII para los clientes que no lo entienden.
    expect($disposition)
        ->toContain("filename*=utf-8''Mart%C3%ADn%20N%C3%BA%C3%B1ez.pdf")
        ->toContain('filename="Martin Nunez.pdf"');
});

it('no deja descargar el documento de otra persona', function (): void {
    $document = Document::factory()->for(subscribedUser())->create();

    $this->actingAs(subscribedUser())
        ->get(route('documents.download', $document))
        ->assertForbidden();
});

it('no deja descargar cuando la suscripción venció', function (): void {
    $user = User::factory()->create();
    Subscriber::factory()->for($user)->expired()->create();

    $document = Document::factory()->for($user)->create();

    // La ruta ya está detrás del middleware, que redirige antes de la política.
    $this->actingAs($user)
        ->get(route('documents.download', $document))
        ->assertRedirect(route('subscriptions.edit'));
});

it('no deja abrir el editor de un documento ajeno', function (): void {
    $document = Document::factory()->for(subscribedUser())->create();

    Livewire::actingAs(subscribedUser())
        ->test(Edit::class, ['document' => $document])
        ->assertForbidden();
});

it('sirve la vista previa en línea, no como descarga', function (): void {
    $user = subscribedUser();
    $document = Document::factory()->for($user)->create();

    $response = $this->actingAs($user)->get(route('documents.preview', $document))->assertOk();

    expect($response->headers->get('content-disposition'))->toStartWith('inline;')
        // Sin no-store el iframe sigue mostrando el PDF anterior tras editar.
        ->and($response->headers->get('cache-control'))->toContain('no-store');
});

it('renderiza la carta de presentación con su propia plantilla', function (): void {
    $user = subscribedUser();
    $document = Document::factory()->for($user)->coverLetter()->create();

    $response = $this->actingAs($user)->get(route('documents.download', $document));

    $response->assertOk();
    expect($response->getContent())->toStartWith('%PDF-');
});

it('borra al usuario y con él sus documentos', function (): void {
    $user = subscribedUser();
    Document::factory()->for($user)->count(3)->create();

    $user->delete();

    expect(Document::count())->toBe(0);
});
