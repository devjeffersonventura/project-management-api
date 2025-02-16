<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

Route::get('/tasks', [TaskController::class, 'index']);
Route::get('/tasks/{id}', [TaskController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('tasks', TaskController::class)->except(['index', 'show']);
    Route::get('/projects/{id}/tasks', [TaskController::class, 'getTasksByProject']);
});