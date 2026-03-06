<?php

use Illuminate\Support\Facades\Route;

Route::get('/ping', fn () => ['pong' => true]);

Route::get('/v1/credenciales', [App\Http\Controllers\api\CredencialesController::class, 'index']);
Route::post('/v1/credenciales', [App\Http\Controllers\api\CredencialesController::class, 'store']);
Route::post('/v1/credenciales/buscar', [App\Http\Controllers\api\CredencialesController::class, 'show']);
Route::put('/v1/credenciales', [App\Http\Controllers\api\CredencialesController::class, 'update']);
Route::delete('/v1/credenciales', [App\Http\Controllers\api\CredencialesController::class, 'destroy']);

Route::post('/v1/encriptar', [App\Http\Controllers\api\FuncionesController::class, 'encrypt']);
Route::post('/v1/desencriptar', [App\Http\Controllers\api\FuncionesController::class, 'decrypt']);