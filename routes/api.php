<?php

use App\Http\Controllers\SensorController;

Route::post('/sensors', [SensorController::class, 'store']);
