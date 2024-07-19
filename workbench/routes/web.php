<?php

// phpcs:ignoreFile

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Workbench\EdineiValdameri\Laravel\DynamicValidation\App\Http\Controllers\TestController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::post('/test-store', [TestController::class, 'store'])
    ->name('test.store');
