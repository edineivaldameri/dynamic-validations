<?php

declare(strict_types=1);

use EdineiValdameri\Laravel\DynamicValidation\Models\Rule;
use EdineiValdameri\Laravel\DynamicValidation\Repositories\RuleRepository;
use EdineiValdameri\Laravel\DynamicValidation\Services\JsonSchemaService;
use Illuminate\Support\Collection;
use Mockery\MockInterface;

it('generates a JSON schema based on rules', function () {
    $this->mock(RuleRepository::class, function (MockInterface $mock) {
        $mock->shouldReceive('getRulesByAction')
            ->with('users.store')
            ->andReturn(new Collection([
                new Rule(['field' => 'name', 'rule' => 'string']),
                new Rule(['field' => 'name', 'rule' => 'required']),
                new Rule(['field' => 'name', 'rule' => 'max', 'value' => 255]),
                new Rule(['field' => 'password', 'rule' => 'string']),
                new Rule(['field' => 'password', 'rule' => 'required']),
                new Rule(['field' => 'password', 'rule' => 'min', 'value' => 6]),
                new Rule(['field' => 'password', 'rule' => 'max', 'value' => 20]),
                new Rule(['field' => 'email', 'rule' => 'string']),
                new Rule(['field' => 'email', 'rule' => 'required']),
                new Rule(['field' => 'email', 'rule' => 'email']),
                new Rule(['field' => 'age', 'rule' => 'integer']),
                new Rule(['field' => 'age', 'rule' => 'min', 'value' => 18]),
                new Rule(['field' => 'age', 'rule' => 'max', 'value' => 65]),
                new Rule(['field' => 'birth', 'rule' => 'date']),
                new Rule(['field' => 'birth', 'rule' => 'required']),
                new Rule(['field' => 'active', 'rule' => 'boolean']),
            ]));
    });

    $generator = app(JsonSchemaService::class);
    $schema = $generator->generate('users.store');

    expect($schema)->toMatchArray([
        'type' => 'object',
        'required' => ['name', 'password', 'email', 'birth'],
        'properties' => [
            'name' => [
                'type' => 'string',
                'maxLength' => 255,
            ],
            'password' => [
                'type' => 'string',
                'minLength' => 6,
                'maxLength' => 20,
            ],
            'email' => [
                'type' => 'string',
                'format' => 'email',
            ],
            'age' => [
                'type' => 'integer',
                'minimum' => 18,
                'maximum' => 65,
            ],
            'birth' => [
                'type' => 'string',
                'format' => 'date',
            ],
            'active' => ['type' => 'boolean'],
        ],
    ]);
});

it('adds required fields correctly', function () {
    $this->mock(RuleRepository::class, function (MockInterface $mock) {
        $mock->shouldReceive('getRulesByAction')
            ->with('users.store')
            ->andReturn(new Collection([
                new Rule(['field' => 'email', 'rule' => 'required']),
                new Rule(['field' => 'password', 'rule' => 'required']),
            ]));
    });

    $generator = app(JsonSchemaService::class);
    $schema = $generator->generate('users.store');

    expect($schema['required'])->toBe(['email', 'password']);
});

it('handles optional fields correctly', function () {
    $this->mock(RuleRepository::class, function (MockInterface $mock) {
        $mock->shouldReceive('getRulesByAction')
            ->with('users.store')
            ->andReturn(new Collection([
                new Rule(['field' => 'name', 'rule' => 'string']),
                new Rule(['field' => 'age', 'rule' => 'integer']),
                new Rule(['field' => 'age', 'rule' => 'min', 'value' => 18]),
            ]));
    });

    $generator = app(JsonSchemaService::class);
    $schema = $generator->generate('users.store');

    expect($schema['required'])->toBeEmpty()
        ->and($schema['properties'])->toMatchArray([
            'name' => ['type' => 'string'],
            'age' => [
                'type' => 'integer',
                'minimum' => 18,
            ],
        ]);
});
