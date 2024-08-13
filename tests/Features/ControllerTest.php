<?php

declare(strict_types=1);

use EdineiValdameri\Laravel\DynamicValidation\Models\Rule;

it('validates the return of the dynamic controller\'s index method', function () {
    $response = $this->get('dynamic');
    $response->assertOk();
});

it('validates the return of the dynamic controller\'s store method', function () {
    $response = $this->post('dynamic', [
        'action' => 'test',
        'field' => 'test',
        'rule' => 'required',
    ]);
    $response->assertOk();
    $this->assertDatabaseHas(Rule::class, [
        'action' => 'test',
        'field' => 'test',
        'rule' => 'required',
    ]);
});

it('validates the return of the dynamic controller\'s update method', function () {
    $rule = Rule::query()->create([
        'action' => 'test',
        'field' => 'test',
        'rule' => 'required',
    ]);
    $response = $this->put("dynamic/{$rule->getKey()}", [
        'action' => 'action',
        'field' => 'field',
        'rule' => 'nullable',
    ]);
    $response->assertOk();
    $this->assertDatabaseHas(Rule::class, [
        'id' => $rule->getKey(),
        'action' => 'action',
        'field' => 'field',
        'rule' => 'nullable',
    ]);
});

it('validates the return of the dynamic controller\'s show method', function () {
    Rule::query()->create([
        'action' => 'test',
        'field' => 'test',
        'rule' => 'required',
    ]);
    $response = $this->get('dynamic/test');
    $response->assertOk();
    $response->assertJsonFragment([
        'action' => 'test',
        'field' => 'test',
        'rule' => 'required',
    ]);
});
