<?php

declare(strict_types=1);

use EdineiValdameri\Laravel\DynamicValidation\Http\Controllers\DynamicController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->group(function () {
    Route::resource('dynamic', DynamicController::class)->only([
        'index',
        'store',
        'update',
    ]);
    Route::get('dynamic/{action}', [DynamicController::class, 'show']);
});
