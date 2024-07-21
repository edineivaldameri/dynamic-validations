<?php

declare(strict_types=1);

namespace EdineiValdameri\Laravel\DynamicValidation\Repositories;

use EdineiValdameri\Laravel\DynamicValidation\Http\Requests\DynamicFormRequest;
use EdineiValdameri\Laravel\DynamicValidation\Models\Rule;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class RuleRepository
{
    /**
     * @return LengthAwarePaginator<Rule>
     */
    public function list(): LengthAwarePaginator
    {
        /** @var LengthAwarePaginator<Rule> $rules */
        $rules = Rule::query()
            ->visible()
            ->paginate();

        return $rules;
    }

    public function store(DynamicFormRequest $request): Rule
    {
        /** @var Rule $rule */
        $rule = Rule::query()->create($request->validated());

        return $rule;
    }

    public function update(DynamicFormRequest $request, int $id): Rule
    {
        $rule = Rule::query()->findOrFail($id);

        /** @var Rule $rule */
        $rule->update($request->validated());

        return $rule;
    }

    /**
     * @return Collection<int, Rule>
     */
    public function getRulesByAction(string $action): Collection
    {
        /** @var Collection<int, Rule> $rules */
        $rules = Cache::rememberForever('dynamic-validations.actions.' . $action, function () use ($action) {
            return Rule::query()
                ->where('action', $action)
                ->get();
        });

        return $rules;
    }

    /**
     * @return array<string, string>
     *
     * @phpstan-return array<string, string>
     */
    public function getMessagesByAction(string $action): array
    {
        /** @var array<string, string> $messages */
        $messages = Cache::rememberForever('dynamic-validations.messages.' . $action, function () use ($action) {
            return Rule::query()
                ->select(['field', 'message'])
                ->where('action', $action)
                ->get()
                ->pluck('message', 'field')
                ->filter()
                ->toArray();
        });

        return $messages;
    }
}
