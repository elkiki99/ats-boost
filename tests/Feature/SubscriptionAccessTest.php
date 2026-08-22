<?php

use App\Models\Subscriber;
use App\Models\User;

/**
 * Antes había tres definiciones distintas de "suscripción vigente": una en el
 * modelo, una en cada middleware. Estos tests fijan la única que queda.
 */
it('reconoce como suscrito a quien tiene una suscripción autorizada y vigente', function (): void {
    $user = User::factory()->create();
    Subscriber::factory()->for($user)->create();

    expect($user->isSubscribed())->toBeTrue()
        ->and($user->subscription)->not->toBeNull();
});

it('no reconoce una suscripción vencida', function (): void {
    $user = User::factory()->create();
    Subscriber::factory()->for($user)->expired()->create();

    expect($user->isSubscribed())->toBeFalse()
        ->and($user->subscription)->toBeNull();
});

it('no reconoce una suscripción cancelada y ya vencida', function (): void {
    $user = User::factory()->create();
    Subscriber::factory()->for($user)->cancelled()->expired()->create();

    expect($user->isSubscribed())->toBeFalse();
});

it('mantiene el acceso de una cancelada hasta que termine el período pago', function (): void {
    $user = User::factory()->create();
    $subscriber = Subscriber::factory()->for($user)->cancelled()->create();

    // Cancelada pero con ends_at futuro: sigue teniendo acceso pagado, aunque
    // ya no cuente como suscripción activa para renovar.
    expect($subscriber->isCancelledButActive())->toBeTrue()
        ->and($user->isSubscribed())->toBeFalse();
});

it('toma la suscripción que vence más tarde cuando hay varias', function (): void {
    $user = User::factory()->create();

    Subscriber::factory()->for($user)->create(['ends_at' => now()->addDays(3)]);
    $latest = Subscriber::factory()->for($user)->create(['ends_at' => now()->addDays(40)]);

    expect($user->subscription?->id)->toBe($latest->id);
});

it('detecta el período de prueba', function (): void {
    $user = User::factory()->create();
    Subscriber::factory()->for($user)->onTrial()->create();

    expect($user->onTrial())->toBeTrue()
        ->and($user->isSubscribed())->toBeTrue();
});

it('redirige a planes y avisa una sola vez', function (): void {
    $this->actingAs(User::factory()->create())
        ->get(route('resume.tailor'))
        ->assertRedirect(route('subscriptions.edit'))
        ->assertSessionHas('subscription_required');

    // El aviso es flash: en la visita siguiente ya no está.
    $this->get(route('subscriptions.edit'));

    expect(session()->has('subscription_required'))->toBeFalse();
});

it('manda a login a quien no inició sesión', function (): void {
    $this->get(route('resume.tailor'))->assertRedirect(route('login'));
});
