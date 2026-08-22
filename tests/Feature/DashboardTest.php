<?php

use App\Models\User;

test('guests are redirected to the login page', function () {
    // Por nombre de ruta y no por URL: el panel vive en /panel, no en
    // /dashboard, y esta prueba llevaba tiempo fallando contra la URL del
    // starter kit.
    $this->get(route('dashboard'))->assertRedirect(route('login'));
});

test('authenticated users can visit the dashboard', function () {
    $this->actingAs(User::factory()->create());

    $this->get(route('dashboard'))->assertOk();
});

test('the dashboard invites unsubscribed users to pick a plan', function () {
    $this->actingAs(User::factory()->create());

    $this->get(route('dashboard'))
        ->assertOk()
        ->assertSee('Necesitás una suscripción activa');
});

test('the dashboard does not nag subscribed users', function () {
    $this->actingAs(subscribedUser());

    $this->get(route('dashboard'))
        ->assertOk()
        ->assertDontSee('Necesitás una suscripción activa');
});
