<?php

declare(strict_types=1);

namespace EdineiValdameri\Laravel\DynamicValidation\Models;

use EdineiValdameri\Laravel\DynamicValidation\Models\Builders\RuleBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\HasBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $action
 * @property string $field
 * @property string $rule
 * @property string $value
 * @property string $message
 *
 * @property array<string, string> $casts
 * @property array<int, string> $fillable
 */
class Rule extends Model
{
    /** @use HasBuilder<RuleBuilder> */
    use HasBuilder;

    use HasFactory; // @phpstan-ignore-line
    use SoftDeletes;

    protected static string $builder = RuleBuilder::class;

    protected $casts = [
        'visible' => 'boolean',
    ];

    protected $fillable = [
        'action',
        'field',
        'rule',
        'value',
        'visible',
        'message',
    ];
}
