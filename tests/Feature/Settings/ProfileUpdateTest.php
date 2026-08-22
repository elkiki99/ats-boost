<?php

use App\Livewire\Settings\Profile;
use App\Models\User;
use Livewire\Livewire;

test('profile page is displayed', function () {
    $this->actingAs(User::factory()->create());

    $this->get(route('profile.edit'))->assertOk();
});

test('profile information can be updated', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test(Profile::class)
        ->set('name', 'Test User')
        ->call('updateProfileInformation')
        ->assertHasNoErrors();

    expect($user->refresh()->name)->toEqual('Test User');
});

test('the email address is not editable from the profile form', function () {
    $user = User::factory()->create(['email' => 'original@ejemplo.com']);

    $this->actingAs($user);

    Livewire::test(Profile::class)
        ->set('email', 'otro@ejemplo.com')
        ->set('name', 'Test User')
        ->call('updateProfileInformation')
        ->assertHasNoErrors();

    // El correo es la identidad de la cuenta y queda atado al pagador de
    // Mercado Pago: el campo está deshabilitado en la vista y el componente
    // tampoco lo valida ni lo guarda.
    expect($user->refresh()->email)->toEqual('original@ejemplo.com')
        ->and($user->email_verified_at)->not->toBeNull();
});

test('email verification status is unchanged when email address is unchanged', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = Livewire::test(Profile::class)
        ->set('name', 'Test User')
        ->set('email', $user->email)
        ->call('updateProfileInformation');

    $response->assertHasNoErrors();

    expect($user->refresh()->email_verified_at)->not->toBeNull();
});

test('user can delete their account', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = Livewire::test('settings.delete-user-form')
        ->set('password', 'password')
        ->call('deleteUser');

    $response
        ->assertHasNoErrors()
        ->assertRedirect('/');

    expect($user->fresh())->toBeNull();
    expect(auth()->check())->toBeFalse();
});

test('correct password must be provided to delete account', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = Livewire::test('settings.delete-user-form')
        ->set('password', 'wrong-password')
        ->call('deleteUser');

    $response->assertHasErrors(['password']);

    expect($user->fresh())->not->toBeNull();
});
