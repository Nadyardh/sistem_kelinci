<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;
use Exception;

class AiController extends Controller
{
    public function recommendation()
    {
        $latestSensor = Sensor::latest()->first();

        if (!$latestSensor) {
            return response()->json([
                'message' => 'Belum ada data sensor',
                'success' => false
            ], 404);
        }

        try {
            $prompt = "Anda adalah asisten kesehatan kelinci.

Data kandang:
- Suhu: {$latestSensor->temperature}°C
- Kelembapan: {$latestSensor->humidity}%
- THI: " . round($latestSensor->thi, 2) . "
- Status: {$latestSensor->status}

Berikan rekomendasi singkat maksimal 3 poin.
Gunakan bahasa Indonesia yang mudah dipahami peternak.";

            $response = OpenAI::chat()->create([
                'model' => env('OPENAI_MODEL', 'gpt-4-mini'),
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Anda adalah ahli kesehatan dan manajemen kelinci yang berpengalaman.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'max_tokens' => 200,
                'temperature' => 0.7
            ]);

            if (!isset($response->choices[0]->message->content)) {
                throw new Exception('Response format tidak valid dari OpenAI');
            }

            return response()->json([
                'success' => true,
                'recommendation' => $response->choices[0]->message->content,
                'sensor_data' => [
                    'temperature' => $latestSensor->temperature,
                    'humidity' => $latestSensor->humidity,
                    'thi' => round($latestSensor->thi, 2),
                    'status' => $latestSensor->status,
                    'updated_at' => $latestSensor->created_at->diffForHumans()
                ]
            ]);

        } catch (Exception $e) {
            \Log::error('OpenAI API Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil rekomendasi AI',
                'error' => env('APP_DEBUG') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }
}
