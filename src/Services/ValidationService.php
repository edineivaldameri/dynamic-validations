<?php

declare(strict_types=1);

namespace EdineiValdameri\Laravel\DynamicValidation\Services;

use EdineiValdameri\Laravel\DynamicValidation\Models\Rule;
use EdineiValdameri\Laravel\DynamicValidation\Repositories\RuleRepository;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Support\Facades\Validator;

class ValidationService
{
    public function __construct(
        protected RuleRepository $ruleRepository
    ) {
    }

    /**
     * @phpstan-return array<string, array<int, string>|string>
     */
    public function getValidationRules(string $action): array
    {
        $rules = $this->ruleRepository->getRulesByAction($action);

        $validationRules = [];

        /**
         * @var Rule $rule
         */
        foreach ($rules as $rule) {
            if (!isset($validationRules[$rule->field])) {
                $validationRules[$rule->field] = [];
            }

            if ($rule->value) {
                $validationRules[$rule->field][] = "{$rule->rule}:{$rule->value}";
            } else {
                $validationRules[$rule->field][] = $rule->rule;
            }
        }

        foreach ($validationRules as $field => $ruleArray) {
            $validationRules[$field] = implode('|', $ruleArray);
        }

        return $validationRules;
    }

    /**
     * @return array<string, string>
     *
     * @phpstan-return array<string, string>
     */
    public function getValidationMessages(string $action): array
    {
        return $this->ruleRepository
            ->getMessagesByAction($action);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function validate(string $action, array $data): ValidatorContract
    {
        $rules = $this->getValidationRules($action);
        $messages = $this->getValidationMessages($action);

        return Validator::make($data, $rules, $messages);
    }
}
