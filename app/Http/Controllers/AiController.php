<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;

class AiController extends Controller
{
    public function recommendation()
    {
        $latestSensor = Sensor::latest()->first();

        if (!$latestSensor) {
            return response()->json([
                'message' => 'Belum ada data sensor'
            ], 404);
        }

        $prompt = "
        Anda adalah asisten kesehatan kelinci.

        Data kandang:
        - Suhu: {$latestSensor->temperature}°C
        - Kelembapan: {$latestSensor->humidity}%
        - THI: {$latestSensor->thi}
        - Status: {$latestSensor->status}

        Berikan rekomendasi singkat maksimal 3 poin.
        Gunakan bahasa Indonesia yang mudah dipahami peternak.
        ";

        $response = OpenAI::chat()->create([
            'model' => env('OPENAI_MODEL', 'gpt-5-mini'),
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ],
            'max_tokens' => 150
        ]);

        return response()->json([
            'recommendation' =>
                $response->choices[0]->message->content
        ]);
    }
}
