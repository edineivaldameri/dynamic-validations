<?php

declare(strict_types=1);

use EdineiValdameri\Laravel\DynamicValidation\Models\Rule;

it('validates CPF field with custom message', function () {
    Rule::create([
        'action' => 'test.store',
        'field' => 'cpf',
        'rule' => 'cpf',
        'value' => true,
        'message' => 'O campo CPF é inválido.',
    ]);

    $this->post(route('test.store'), [
        'cpf' => '123.456.789-00',
    ])->assertSessionHasErrors([
        'cpf' => 'O campo CPF é inválido.',
    ]);
});
