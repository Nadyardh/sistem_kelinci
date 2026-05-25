<?php

namespace App\Http\Controllers\Api;

use App\Models\Sensor;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SensorController extends Controller
{
    public function store(Request $request)
    {
        $temperature = $request->temperature;
        $humidity = $request->humidity;

        // Hitung THI sederhana
        $thi = $temperature - ((0.31 - 0.31 * $humidity/100) * ($temperature - 14.4));

        // Klasifikasi
        if ($thi < 27) {
            $status = 'Normal';
        } elseif ($thi < 30) {
            $status = 'Mild Stress';
        } elseif ($thi < 33) {
            $status = 'Moderate Stress';
        } else {
            $status = 'Severe Stress';
        }

        $sensor = Sensor::create([
            'temperature' => $temperature,
            'humidity' => $humidity,
            'thi' => $thi,
            'status' => $status
        ]);

        return response()->json([
            'message' => 'Data saved',
            'data' => $sensor
        ]);
    }
}
