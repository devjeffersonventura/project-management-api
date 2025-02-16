<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::post('/users', [UserController::class, 'store']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('users', UserController::class)->except(['store']);
    Route::patch('/users/{id}/role', [UserController::class, 'updateRole']);
});