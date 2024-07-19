<?php

declare(strict_types=1);

namespace EdineiValdameri\Laravel\DynamicValidation\Models\Builders;

use EdineiValdameri\Laravel\DynamicValidation\Models\Rule;
use Illuminate\Database\Eloquent\Builder;

/**
 * @extends Builder<Rule>
 */
class RuleBuilder extends Builder
{
    public function visible(): self
    {
        return $this->where('visible', true);
    }
}
