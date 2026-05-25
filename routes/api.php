<?php

use App\Http\Controllers\Api\SensorController;

Route::post('/sensors', [SensorController::class, 'store']);
