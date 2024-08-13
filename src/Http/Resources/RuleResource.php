<?php

declare(strict_types=1);

namespace EdineiValdameri\Laravel\DynamicValidation\Http\Resources;

use EdineiValdameri\Laravel\DynamicValidation\Models\Rule;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class RuleResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array<string, mixed>|Arrayable<int, Rule>|JsonSerializable
     */
    public function toArray(Request $request)
    {
        return parent::toArray($request);
    }
}
