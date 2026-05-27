<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AiController;

Route::get('/', [DashboardController::class, 'index']);

Route::post('/ai-recommendation', [
    AiController::class,
    'recommendation'
]);
