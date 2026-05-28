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

            $model = $this->resolveOpenAIModel();

            $response = OpenAI::chat()->create([
                'model' => $model,
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
                'max_completion_tokens' => 200,
                'temperature' => 1
            ]);

            $recommendation = $response->choices[0]->message->content ?? '';

            if (!trim($recommendation)) {
                throw new \Exception('Rekomendasi AI kosong dari model ' . $model);
            }

            return response()->json([
                'success' => true,
                'recommendation' => trim($recommendation),
                'sensor_data' => [
                    'temperature' => $latestSensor->temperature,
                    'humidity' => $latestSensor->humidity,
                    'thi' => round($latestSensor->thi, 2),
                    'status' => $latestSensor->status,
                    'updated_at' => $latestSensor->created_at->diffForHumans()
                ]
            ]);

        } catch (\Throwable $e) {
            \Log::error('OpenAI API Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil rekomendasi AI',
                'error' => env('APP_DEBUG') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    private function resolveOpenAIModel(): string
    {
        $model = env('OPENAI_MODEL', 'gpt-5.4-mini');

        $unsupportedModels = [
            'gpt-5-mini',
            'gpt-5-mini-2025-08-07',
        ];

        if (in_array($model, $unsupportedModels, true)) {
            return 'gpt-5.4-mini';
        }

        return $model;
    }
}
