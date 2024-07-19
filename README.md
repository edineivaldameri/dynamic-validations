# DYNAMIC VALIDATIONS

You always need to build validations into your code to ensure the integrity of the data received by your system. When a rule needs to be changed, removed or added you need to change its source code for this to happen. With the `dynamic-validations` package this no longer happens, you can have different validations for different clients without the need for code changes. You just change, remove or add the validations in the database and they will be working dynamically in the next request that your system receives.

## Installation

You can install the package via composer:

```bash
composer require edineivaldameri/dynamic-validations
```

## Validations

To carry out validation it is very simple: Just import the `DynamicFormRequest` request into your controller method.

```php
class TestController
{
    public function store(DynamicFormRequest $request): JsonResponse
    {
        $request->validate(); // This will validate the request according to the rules registered in the database
        return response()->json([
            'message' => 'Validated successfully',
        ], 200);
    }
}
```

To save a validation rule in the database, you can use the `create` method of the `Rule` model.

```php
    Rule::query()->create([
        'action' => 'test.store', // Action that the rule will be applied to
        'field' => 'name', // Field that the rule will be applied to
        'rule' => 'required', // Rule to be applied
    ]); // This will create a rule that the name field is required

    Rule::query()->create([
        'action' => 'test.store', // Action that the rule will be applied to
        'field' => 'name', // Field that the rule will be applied to
        'rule' => 'min', // Rule to be applied
        'value' => '5', // Value to be compared
        'message' => 'The name must be at least 5 characters long', // Message to be returned in case of error
    ]); // This will create a rule that the name field must have at least 5 characters
    // The field `action` is the name of the controller method that the rule will be applied to
    // The field `value` and 'message' are optional
```

But you don't need to worry, because `dynamic-validations` already has its own controller to receive requests and save and edit its rules in the database.

```php
    $this->post('dynamic', [
        'action' => 'test.store',
        'field' => 'name',
        'rule' => 'required',
    ]);
    // OR
    $this->put("dynamic/{$ruleId}", [
        'action' => 'test.store',
        'field' => 'name',
        'rule' => 'nullable',
    ]);
```



## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Edinei Valdameri](https://github.com/edineivaldameri)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
