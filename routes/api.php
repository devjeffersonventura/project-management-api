<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    require __DIR__ . '/project.php';
    require __DIR__ . '/task.php';
});