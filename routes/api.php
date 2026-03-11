<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ShortUrlController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/user', [UserController::class, 'show']);
    Route::match(['put', 'patch'], '/user', [UserController::class, 'update']);
    Route::delete('/user', [UserController::class, 'destroy']);

    Route::apiResource('urls', ShortUrlController::class);
});
