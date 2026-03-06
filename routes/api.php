<?php

use Illuminate\Support\Facades\Route;

Route::get('/ping', fn () => ['pong' => true]);

Route::prefix('v1')->group(function () {
    Route::apiResource('users', \App\Http\Controllers\Api\V1\UserController::class);
});