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
            $prompt = "Anda adalah asisten kesehatan kelinci yang berpengalaman.\n\n" .
                "Data kandang:\n" .
                "- Suhu: {$latestSensor->temperature}°C\n" .
                "- Kelembapan: {$latestSensor->humidity}%\n" .
                "- THI: " . round($latestSensor->thi, 2) . "\n" .
                "- Status: {$latestSensor->status}\n\n" .
                "Buat rekomendasi yang jelas dan praktis untuk peternak. RESPON HANYA DALAM BENTUK JSON VALID (TIDAK ADA TEKS LAIN DI LUAR JSON) dengan struktur:\n" .
                "{\n" .
                "  \"summary\": \"ringkasan singkat\",\n" .
                "  \"recommendations\": [\"rekomendasi 1\", \"rekomendasi 2\"],\n" .
                "  \"actions\": [\"tindakan segera 1\", \"tindakan segera 2\"]\n" .
                "}\n\n" .
                "Gunakan bahasa Indonesia yang mudah dipahami. Jangan sertakan penjelasan atau meta di luar objek JSON.";

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
                'max_tokens' => 10000,
                'temperature' => 0.7
            ]);

            $recommendation = $response->choices[0]->message->content ?? '';

            if (!trim($recommendation)) {
                throw new \Exception('Rekomendasi AI kosong dari model ' . $model);
            }

            // Try to parse JSON response from the model
            $parsed = json_decode(trim($recommendation), true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($parsed)) {
                $html = '';
                if (!empty($parsed['summary'])) {
                    $html .= '<p>' . e($parsed['summary']) . '</p>';
                }
                if (!empty($parsed['recommendations']) && is_array($parsed['recommendations'])) {
                    $html .= '<ul>';
                    foreach ($parsed['recommendations'] as $item) {
                        $html .= '<li>' . e($item) . '</li>';
                    }
                    $html .= '</ul>';
                }
                if (!empty($parsed['actions']) && is_array($parsed['actions'])) {
                    $html .= '<h6>Tindakan yang disarankan</h6><ol>';
                    foreach ($parsed['actions'] as $a) {
                        $html .= '<li>' . e($a) . '</li>';
                    }
                    $html .= '</ol>';
                }

                $recommendation_html = $html ?: nl2br(e($recommendation));
            } else {
                // Fallback: render raw text preserving line breaks
                $recommendation_html = nl2br(e($recommendation));
            }

            return response()->json([
                'success' => true,
                'recommendation' => trim($recommendation),
                'recommendation_html' => $recommendation_html,
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
