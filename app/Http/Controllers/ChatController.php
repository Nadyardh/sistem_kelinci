<?php

namespace App\Http\Controllers;

use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\Sensor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use OpenAI\Laravel\Facades\OpenAI;
use Throwable;

class ChatController extends Controller
{
    private const MAX_CONTEXT_MESSAGES = 20;

    public function store(Request $request)
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:4000'],
            'conversation_id' => ['nullable', 'uuid'],
        ]);

        $sessionKey = $this->sessionKey($request);
        $conversation = null;

        if (! empty($validated['conversation_id'])) {
            $conversation = ChatConversation::query()
                ->where('session_key', $sessionKey)
                ->find($validated['conversation_id']);

            abort_unless($conversation, 404);
        }

        $history = $conversation
            ? $conversation->messages()
                ->latest('id')
                ->limit(self::MAX_CONTEXT_MESSAGES)
                ->get()
                ->reverse()
                ->map(fn (ChatMessage $message) => [
                    'role' => $message->role,
                    'content' => $message->content,
                ])
                ->values()
                ->all()
            : [];

        if (! config('openai.api_key')) {
            return response()->json([
                'success' => false,
                'message' => 'Integrasi OpenAI belum dikonfigurasi. Isi OPENAI_API_KEY di file .env.',
            ], 503);
        }

        $systemPrompt = $this->systemPrompt();

        try {
            $response = OpenAI::chat()->create([
                'model' => config('openai.model'),
                'messages' => array_merge(
                    [['role' => 'system', 'content' => $systemPrompt]],
                    $history,
                    [['role' => 'user', 'content' => $validated['message']]]
                ),
                'max_completion_tokens' => 800,
            ]);

            $reply = $response->choices[0]->message->content ?? null;

            if (! is_string($reply) || trim($reply) === '') {
                throw new \RuntimeException('OpenAI returned an empty chat response.');
            }
        } catch (Throwable $exception) {
            Log::error('OpenAI chat request failed.', [
                'exception' => $exception,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Chat AI sedang tidak tersedia. Silakan coba lagi.',
            ], 502);
        }

        $conversation = DB::transaction(function () use ($conversation, $sessionKey, $validated, $reply) {
            $conversation ??= ChatConversation::create([
                'id' => (string) Str::uuid(),
                'session_key' => $sessionKey,
            ]);

            $conversation->messages()->createMany([
                ['role' => 'user', 'content' => $validated['message']],
                ['role' => 'assistant', 'content' => trim($reply)],
            ]);

            return $conversation;
        });

        return response()->json([
            'success' => true,
            'conversation_id' => $conversation->id,
            'reply' => trim($reply),
        ]);
    }

    public function show(Request $request, string $conversationId)
    {
        abort_unless(Str::isUuid($conversationId), 404);

        $conversation = ChatConversation::query()
            ->where('session_key', $this->sessionKey($request))
            ->findOrFail($conversationId);

        return response()->json([
            'conversation_id' => $conversation->id,
            'messages' => $conversation->messages()
                ->latest('id')
                ->limit(100)
                ->get(['role', 'content'])
                ->reverse()
                ->values(),
        ]);
    }

    private function sessionKey(Request $request): string
    {
        if (! $request->session()->has('chat_session_key')) {
            $request->session()->put('chat_session_key', (string) Str::uuid());
        }

        return $request->session()->get('chat_session_key');
    }

    private function systemPrompt(): string
    {
        $sensor = Sensor::query()->latest()->first();
        $sensorContext = $sensor
            ? sprintf(
                'Data sensor kandang terakhir: suhu %s°C, kelembapan %s%%, THI %s, status %s.',
                $sensor->temperature,
                $sensor->humidity,
                $sensor->thi === null ? 'tidak tersedia' : round($sensor->thi, 2),
                $sensor->status
            )
            : 'Belum ada data sensor kandang.';

        return 'Anda adalah asisten berbahasa Indonesia yang membantu peternak kelinci '
            .'dengan informasi perawatan dan kondisi kandang. Berikan jawaban praktis, '
            .'jelas, dan berhati-hati; jangan mengklaim sebagai dokter hewan atau '
            .'menggantikan pemeriksaan profesional. Sarankan menghubungi dokter hewan '
            .'untuk kondisi darurat atau gejala serius. '
            .$sensorContext;
    }
}
