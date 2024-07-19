<?php

declare(strict_types=1);

namespace EdineiValdameri\Laravel\DynamicValidation\Providers;

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
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/dynamic.php', 'dynamic');
        $this->mergeConfigFrom(__DIR__ . '/../../config/insights.php', 'insights');
    }
}
