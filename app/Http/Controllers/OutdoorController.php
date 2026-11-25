<?php

namespace App\Http\Controllers;

use App\Exceptions\OpenWeatherException;
use App\Services\OpenWeatherService;
use Illuminate\Support\Facades\Log;

class OutdoorController extends Controller
{
    public function store(OpenWeatherService $openWeather)
    {
        try {
            $outdoor = $openWeather->fetchAndStore();
        } catch (OpenWeatherException $exception) {
            Log::warning('Manual OpenWeather update failed.', [
                'exception_type' => $exception->getPrevious() ? $exception->getPrevious()::class : $exception::class,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Data cuaca dari OpenWeather gagal diperbarui.',
            ], 502);
        }

        return response()->json([
            'success' => true,
            'outdoor' => $outdoor,
        ], 201);
    }
}
