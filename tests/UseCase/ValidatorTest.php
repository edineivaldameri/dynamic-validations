<?php

declare(strict_types=1);

use EdineiValdameri\Laravel\DynamicValidation\Models\Rule;
use EdineiValdameri\Laravel\DynamicValidation\Repositories\RuleRepository;
use EdineiValdameri\Laravel\DynamicValidation\Services\ValidationService;
use Illuminate\Support\MessageBag;

it('validates required name field with custom messages', function () {
    Rule::create([
        'action' => 'test.store',
        'field' => 'name',
        'rule' => 'required',
        'value' => null,
        'message' => 'O campo nome é obrigatório.',
    ]);

    $data = [
        'name' => '',
        'email' => 'invalid-email',
    ];

    $repository = new RuleRepository();
    $service = new ValidationService($repository);

    $validator = $service->validate('test.store', $data);

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors())->toBeInstanceOf(MessageBag::class)
        ->and($validator->errors()->get('name'))->toBeArray()
        ->and($validator->errors()->get('name'))->toContain('O campo nome é obrigatório.');
});
