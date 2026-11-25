<?php

use App\Http\Controllers\AiController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OutdoorController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index']);

Route::post('/ai-recommendation', [
    AiController::class,
    'recommendation',
]);

Route::get('/ai-chat/{conversationId}', [ChatController::class, 'show'])
    ->middleware('throttle:60,1')
    ->name('ai-chat.show');

Route::post('/ai-chat', [ChatController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('ai-chat.store');

Route::post('/outdoor/refresh', [OutdoorController::class, 'store'])
    ->middleware('throttle:6,1')
    ->name('outdoor.refresh');
