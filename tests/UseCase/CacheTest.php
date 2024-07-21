<?php

declare(strict_types=1);

use EdineiValdameri\Laravel\DynamicValidation\Models\Rule;
use EdineiValdameri\Laravel\DynamicValidation\Repositories\RuleRepository;
use EdineiValdameri\Laravel\DynamicValidation\Services\ValidationService;
use Illuminate\Support\Facades\Cache;

it('validate cache of rules and messages', function () {
    Cache::flush();
    expect(Cache::get('dynamic-validations.messages.test.store'))->toBeNull()
        ->and(Cache::get('dynamic-validations.actions.test.store'))->toBeNull();

    /** @var Rule $rule */
    $rule = Rule::create([
        'action' => 'test.store',
        'field' => 'name',
        'rule' => 'required',
        'message' => 'O campo nome é obrigatório.',
    ])->fresh();

    $repository = new RuleRepository();
    $service = new ValidationService($repository);
    $service->validate('test.store', []);

    $rules = Cache::get('dynamic-validations.actions.test.store');

    /** @var Rule $cacheRule */
    $cacheRule = $rules->first();

    expect(Cache::get('dynamic-validations.messages.test.store'))->toBe([
        'name' => 'O campo nome é obrigatório.',
    ])->and($rules)
        ->toHaveCount(1)
        ->and($cacheRule)->toBeInstanceOf(Rule::class)
        ->and($cacheRule->toArray())->toBe($rule->toArray());

    $rule->update([
        'rule' => 'nullable',
    ]);
    expect(Cache::get('dynamic-validations.messages.test.store'))->toBeNull()
        ->and(Cache::get('dynamic-validations.actions.test.store'))->toBeNull();
});
