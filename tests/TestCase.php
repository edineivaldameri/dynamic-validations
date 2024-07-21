<?php

declare(strict_types=1);

namespace EdineiValdameri\Laravel\DynamicValidation\Tests;

use EdineiValdameri\Laravel\DynamicValidation\Providers\DynamicValidationServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use LaravelLegends\PtBrValidator\ValidatorProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Workbench\EdineiValdameri\Laravel\DynamicValidation\App\Providers\WorkbenchServiceProvider;

class TestCase extends Orchestra
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        /** @phpstan-ignore-next-line */
        Factory::guessFactoryNamesUsing(function (string $model) {
            $namespaces = [
                'EdineiValdameri\\Laravel\\DynamicValidation\\Database\\Factories\\' . class_basename($model) . 'Factory',
                'Workbench\\EdineiValdameri\\Laravel\\DynamicValidation\\Database\\Factories\\' . class_basename($model) . 'Factory',
            ];

            foreach ($namespaces as $option) {
                if (class_exists($option)) {
                    return $option;
                }
            }

            return $model;
        });

        /** @phpstan-ignore-next-line */
        Factory::guessModelNamesUsing(function (string $factory) {
            $namespaces = [
                'EdineiValdameri\\Laravel\\DynamicValidation\\Models\\' . Str::replaceLast('Factory', '', class_basename($factory)),
                'Workbench\\EdineiValdameri\\Laravel\\DynamicValidation\\App\\Models\\' . Str::replaceLast('Factory', '', class_basename($factory)),
            ];

            foreach ($namespaces as $option) {
                if (class_exists($option)) {
                    return $option;
                }
            }

            return $factory;
        });
    }

    protected function getPackageProviders($app): array
    {
        return [
            DynamicValidationServiceProvider::class,
            WorkbenchServiceProvider::class,
            ValidatorProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');
    }
}
