<?php

declare(strict_types=1);

namespace EdineiValdameri\Laravel\DynamicValidation\Database\Factories;

use EdineiValdameri\Laravel\DynamicValidation\Models\Rule;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Rule>
 */
class RuleFactory extends Factory
{
    protected $model = Rule::class;

    public function definition(): array
    {
        return [
            'action' => $this->faker->word,
            'field' => $this->faker->word,
            'rule' => $this->faker->word,
            'value' => $this->faker->word,
            'message' => $this->faker->word,
        ];
    }
}
