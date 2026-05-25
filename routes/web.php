<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SensorController;


Route::get('/', function () {
    return view('welcome');
});

Route::post('/sensors', [SensorController::class, 'store']);
