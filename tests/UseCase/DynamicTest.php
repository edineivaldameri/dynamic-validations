<?php

declare(strict_types=1);

use EdineiValdameri\Laravel\DynamicValidation\Models\Rule;
use Workbench\EdineiValdameri\Laravel\DynamicValidation\App\Models\User;

it('validates required name field with custom messages', function () {
    Rule::create([
        'action' => 'test.store',
        'field' => 'name',
        'rule' => 'required',
        'value' => null,
        'message' => 'O campo nome é obrigatório.',
    ]);

    $this->post(route('test.store'), [])
        ->assertSessionHasErrors([
            'name' => 'O campo nome é obrigatório.',
        ]);
});

it('validates the name field is a string and max 255 characters with custom message', function () {
    Rule::create([
        'action' => 'test.store',
        'field' => 'name',
        'rule' => 'max',
        'value' => '255',
        'message' => 'O campo nome não pode ter mais de 255 caracteres.',
    ]);

    $this->post(route('test.store'), [
        'name' => str_repeat('a', 256),
        'email' => 'test@example.com',
    ])->assertSessionHasErrors([
        'name' => 'O campo nome não pode ter mais de 255 caracteres.',
    ]);
});

it('validates the email field is a valid email with custom message', function () {
    Rule::create([
        'action' => 'test.store',
        'field' => 'email',
        'rule' => 'email',
        'value' => null,
        'message' => 'O campo email deve ser um endereço de email válido.',
    ]);

    $this->post(route('test.store'), [
        'name' => 'Test User',
        'email' => 'invalid-email',
    ])->assertSessionHasErrors([
        'email' => 'O campo email deve ser um endereço de email válido.',
    ]);
});

it('validates that email field must be unique with custom message', function () {
    Rule::create([
        'action' => 'test.store',
        'field' => 'email',
        'rule' => 'unique',
        'value' => 'users,email',
        'message' => 'O campo email já está em uso.',
    ]);

    User::create([
        'name' => 'Existing User',
        'email' => 'test@example.com',
    ]);

    $this->post(route('test.store'), [
        'name' => 'New User',
        'email' => 'test@example.com',
    ])->assertSessionHasErrors([
        'email' => 'O campo email já está em uso.',
    ]);
});
