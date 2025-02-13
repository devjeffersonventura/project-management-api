<?php 

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;

Route::get('/projects', [ProjectController::class, 'index']);
Route::get('/projects/{id}', [ProjectController::class, 'show']);


Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('projects', ProjectController::class)->except(['index', 'show']);
});
