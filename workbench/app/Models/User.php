<?php

declare(strict_types=1);

namespace Workbench\EdineiValdameri\Laravel\DynamicValidation\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email'];
}
