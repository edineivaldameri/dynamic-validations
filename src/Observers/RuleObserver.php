<?php

declare(strict_types=1);

namespace EdineiValdameri\Laravel\DynamicValidation\Observers;

use EdineiValdameri\Laravel\DynamicValidation\Models\Rule;
use Illuminate\Support\Facades\Cache;

class RuleObserver
{
    public function saved(Rule $rule): void
    {
        $this->forgetCache($rule);
    }

    private function forgetCache(Rule $rule): void
    {
        Cache::forget('dynamic-validations.actions.' . $rule->action);
        Cache::forget('dynamic-validations.messages.' . $rule->action);
        Cache::forget('dynamic-validations.schema.' . $rule->action);
    }
}
