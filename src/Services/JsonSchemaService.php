<?php

declare(strict_types=1);

namespace EdineiValdameri\Laravel\DynamicValidation\Services;

use EdineiValdameri\Laravel\DynamicValidation\Models\Rule;
use EdineiValdameri\Laravel\DynamicValidation\Repositories\RuleRepository;
use Illuminate\Support\Facades\Cache;

class JsonSchemaService
{
    public function __construct(
        protected RuleRepository $ruleRepository
    ) {
    }

    /**
     * @param string $action
     * @return array<string, mixed>
     */
    public function generate(string $action): array
    {
        /** @var array<string, mixed> $schema */
        $schema = Cache::rememberForever('dynamic-validations.schema.' . $action, function () use ($action) {
            $rules = $this->ruleRepository->getRulesByAction($action);

            $schema = [
                'type' => 'object',
                'properties' => [],
                'required' => [],
            ];

            $rules->each(function (Rule $rule) use (&$schema) {
                $field = $rule->field;

                if (!isset($schema['properties'][$field])) {
                    $schema['properties'][$field] = [];
                }

                $this->mappingRule($schema, $field, $rule);
            });

            return $schema;
        });

        return $schema;
    }

    private function mappingRule(array &$schema, string $field, Rule $rule): void // @phpstan-ignore-line
    {
        $mapping = [
            'string' => ['type' => 'string'],
            'integer' => ['type' => 'integer'],
            'boolean' => ['type' => 'boolean'],
            'email' => ['format' => 'email'],
            'date' => ['type' => 'string', 'format' => 'date'],
        ];

        if (array_key_exists('type', $schema['properties'][$field]) && $schema['properties'][$field]['type'] === 'string') {
            $mapping['min'] = ['minLength' => (int) $rule->value];
            $mapping['max'] = ['maxLength' => (int) $rule->value];
        } else {
            $mapping['min'] = ['minimum' => (int) $rule->value];
            $mapping['max'] = ['maximum' => (int) $rule->value];
        }

        match ($rule->rule) {
            'required' => $schema['required'][] = $field,
            default => isset($mapping[$rule->rule])
                ? $schema['properties'][$field] = array_merge($schema['properties'][$field], $mapping[$rule->rule])
                : null,
        };
    }
}
