<?php

declare(strict_types=1);

use EdineiValdameri\Laravel\DynamicValidation\Models\Rule;
use Workbench\EdineiValdameri\Laravel\DynamicValidation\App\Models\User;

it('validates min length rule', function () {
    Rule::create([
        'action' => 'test.store',
        'field' => 'name',
        'rule' => 'min',
        'value' => 3,
        'message' => 'O campo nome deve ter pelo menos 3 caracteres.',
    ]);

    $response = $this->post(route('test.store'), [
        'name' => 'a',
    ]);

    $response->assertSessionHasErrors([
        'name' => 'O campo nome deve ter pelo menos 3 caracteres.',
    ]);
});

it('validates max length rule', function () {
    Rule::create([
        'action' => 'test.store',
        'field' => 'name',
        'rule' => 'max',
        'value' => 255,
        'message' => 'O campo nome não pode ser maior que 255 caracteres.',
    ]);

    $response = $this->post(route('test.store'), [
        'name' => str_repeat('a', 256),
    ]);

    $response->assertSessionHasErrors([
        'name' => 'O campo nome não pode ser maior que 255 caracteres.',
    ]);
});

it('validates numeric rule', function () {
    Rule::create([
        'action' => 'test.store',
        'field' => 'age',
        'rule' => 'numeric',
        'message' => 'O campo idade deve ser um número.',
    ]);

    $response = $this->post(route('test.store'), [
        'age' => 'not-a-number',
    ]);

    $response->assertSessionHasErrors([
        'age' => 'O campo idade deve ser um número.',
    ]);
});

it('validates confirmed rule', function () {
    Rule::create([
        'action' => 'test.store',
        'field' => 'password',
        'rule' => 'confirmed',
        'message' => 'A confirmação de senha não confere.',
    ]);

    $response = $this->post(route('test.store'), [
        'password' => 'password',
        'password_confirmation' => 'different-password',
    ]);

    $response->assertSessionHasErrors([
        'password' => 'A confirmação de senha não confere.',
    ]);
});

it('validates unique rule', function () {
    Rule::create([
        'action' => 'test.store',
        'field' => 'email',
        'rule' => 'unique:users,email',
        'message' => 'O campo email já está em uso.',
    ]);

    User::create([
        'name' => 'Existing User',
        'email' => 'test@example.com',
    ]);

    $response = $this->post(route('test.store'), [
        'email' => 'test@example.com',
    ]);

    $response->assertSessionHasErrors([
        'email' => 'O campo email já está em uso.',
    ]);
});

it('validates url rule', function () {
    Rule::create([
        'action' => 'test.store',
        'field' => 'website',
        'rule' => 'url',
        'message' => 'O campo website deve ser uma URL válida.',
    ]);

    $response = $this->post(route('test.store'), [
        'website' => 'not-a-url',
    ]);

    $response->assertSessionHasErrors([
        'website' => 'O campo website deve ser uma URL válida.',
    ]);
});

it('validates date rule', function () {
    Rule::create([
        'action' => 'test.store',
        'field' => 'date_of_birth',
        'rule' => 'date',
        'message' => 'O campo data de nascimento deve ser uma data válida.',
    ]);

    $response = $this->post(route('test.store'), [
        'date_of_birth' => 'not-a-date',
    ]);

    $response->assertSessionHasErrors([
        'date_of_birth' => 'O campo data de nascimento deve ser uma data válida.',
    ]);
});

it('validates in rule', function () {
    Rule::create([
        'action' => 'test.store',
        'field' => 'role',
        'rule' => 'in',
        'value' => 'admin,user',
        'message' => 'O campo cargo selecionado é inválido.',
    ]);

    $response = $this->post(route('test.store'), [
        'role' => 'invalid-role',
    ]);

    $response->assertSessionHasErrors([
        'role' => 'O campo cargo selecionado é inválido.',
    ]);
});

it('validates digits_between rule', function () {
    Rule::create([
        'action' => 'test.store',
        'field' => 'pin',
        'rule' => 'digits_between',
        'value' => '4,6',
        'message' => 'O campo PIN deve ter entre 4 e 6 dígitos.',
    ]);

    $response = $this->post(route('test.store'), [
        'pin' => '2',
    ]);

    $response->assertSessionHasErrors([
        'pin' => 'O campo PIN deve ter entre 4 e 6 dígitos.',
    ]);
});

it('validates accepted rule', function () {
    Rule::create([
        'action' => 'test.store',
        'field' => 'terms',
        'rule' => 'accepted',
        'message' => 'O campo termos deve ser aceito.',
    ]);

    $response = $this->post(route('test.store'), [
        'terms' => 'no',
    ]);

    $response->assertSessionHasErrors([
        'terms' => 'O campo termos deve ser aceito.',
    ]);
});

it('validates before rule', function () {
    Rule::create([
        'action' => 'test.store',
        'field' => 'start_date',
        'rule' => 'before',
        'value' => now()->format('Y-m-d'),
        'message' => 'O campo data de início deve ser uma data anterior a hoje.',
    ]);

    $response = $this->post(route('test.store'), [
         'start_date' => now()->addDay()->format('Y-m-d'),
    ]);

    $response->assertSessionHasErrors([
        'start_date' => 'O campo data de início deve ser uma data anterior a hoje.',
    ]);
});

it('validates after rule', function () {
    Rule::create([
        'action' => 'test.store',
        'field' => 'end_date',
        'rule' => 'after',
        'value' => now()->format('Y-m-d'),
        'message' => 'O campo data de término deve ser uma data posterior a hoje.',
    ]);

    $response = $this->post(route('test.store'), [
        'end_date' => now()->subDay()->format('Y-m-d'),
    ]);

    $response->assertSessionHasErrors([
        'end_date' => 'O campo data de término deve ser uma data posterior a hoje.',
    ]);
});

it('validates ipv4 rule', function () {
    Rule::create([
        'action' => 'test.store',
        'field' => 'ip_address',
        'rule' => 'ipv4',
        'message' => 'O campo endereço de IP deve ser um endereço IPv4 válido.',
    ]);

    $response = $this->post(route('test.store'), [
        'ip_address' => 'invalid-ip',
    ]);

    $response->assertSessionHasErrors([
        'ip_address' => 'O campo endereço de IP deve ser um endereço IPv4 válido.',
    ]);
});
