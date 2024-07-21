<?php

declare(strict_types=1);

namespace EdineiValdameri\Laravel\DynamicValidation\Providers;

use EdineiValdameri\Laravel\DynamicValidation\Models\Rule;
use EdineiValdameri\Laravel\DynamicValidation\Observers\RuleObserver;
use Illuminate\Support\ServiceProvider;

class DynamicValidationServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../../routes/dynamic.php');

        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom([
                __DIR__ . '/../../database/migrations',
            ]);
        }

        Rule::observe(RuleObserver::class);
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/dynamic.php', 'dynamic');
    }
}
