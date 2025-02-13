<?php 

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/reports/projects', [ReportController::class, 'projectReport']);
});