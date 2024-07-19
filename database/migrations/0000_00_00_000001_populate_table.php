<?php

declare(strict_types=1);

use EdineiValdameri\Laravel\DynamicValidation\Models\Rule;
use Illuminate\Database\Migrations\Migration;

class PopulateTable extends Migration
{
    private array $actions = [
        'dynamic.store',
        'dynamic.update',
    ];

    private array $rules = [
        [
            'field' => 'action',
            'rule' => 'required',
        ],
        [
            'field' => 'action',
            'rule' => 'string',
        ],
        [
            'field' => 'field',
            'rule' => 'required',
        ],
        [
            'field' => 'field',
            'rule' => 'string',
        ],
        [
            'field' => 'rule',
            'rule' => 'required',
        ],
        [
            'field' => 'rule',
            'rule' => 'string',
        ],
        [
            'field' => 'value',
            'rule' => 'nullable',
        ],
        [
            'field' => 'message',
            'rule' => 'nullable',
        ],
        [
            'field' => 'message',
            'rule' => 'string',
        ],
    ];

    public function up(): void
    {
        foreach ($this->actions as $action) {
            foreach ($this->rules as $rule) {
                Rule::query()->create([
                    'action' => $action,
                    'field' => $rule['field'],
                    'rule' => $rule['rule'],
                    'visible' => false,
                ]);
            }
        }
    }
}
