<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    require __DIR__ . '/project.php';
    require __DIR__ . '/task.php';
    require __DIR__ . '/user.php';
    require __DIR__ . '/auth.php';
    require __DIR__ . '/report.php';
});