<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use OpenAI\Laravel\Facades\OpenAI;
use OpenAI\Resources\Chat;
use OpenAI\Responses\Chat\CreateResponse;
use Tests\TestCase;

class ChatTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_saves_chat_messages_and_sends_recent_history_to_openai(): void
    {
        config(['openai.api_key' => 'test-key']);

        OpenAI::fake([
            CreateResponse::fake([
                'choices' => [
                    ['message' => ['role' => 'assistant', 'content' => 'Halo, saya siap membantu.']],
                ],
            ]),
            CreateResponse::fake([
                'choices' => [
                    ['message' => ['role' => 'assistant', 'content' => 'Pastikan ventilasi kandang baik.']],
                ],
            ]),
        ]);

        $firstResponse = $this->postJson('/ai-chat', [
            'message' => 'Halo',
        ])->assertOk()
            ->assertJsonPath('reply', 'Halo, saya siap membantu.');

        $conversationId = $firstResponse->json('conversation_id');

        $this->postJson('/ai-chat', [
            'message' => 'Bagaimana menjaga kandang?',
            'conversation_id' => $conversationId,
        ])->assertOk()
            ->assertJsonPath('reply', 'Pastikan ventilasi kandang baik.');

        $this->getJson('/ai-chat/'.$conversationId)
            ->assertOk()
            ->assertJsonCount(4, 'messages')
            ->assertJsonPath('messages.3.content', 'Pastikan ventilasi kandang baik.');

        $this->assertDatabaseCount('chat_conversations', 1);
        $this->assertDatabaseCount('chat_messages', 4);

        OpenAI::assertSent(Chat::class, function (string $method, array $parameters): bool {
            return $method === 'create'
                && count($parameters['messages']) === 4
                && $parameters['messages'][1]['content'] === 'Halo'
                && $parameters['messages'][2]['content'] === 'Halo, saya siap membantu.'
                && $parameters['messages'][3]['content'] === 'Bagaimana menjaga kandang?';
        });
    }

    public function test_it_rejects_empty_messages_before_calling_openai(): void
    {
        $this->postJson('/ai-chat', [
            'message' => '',
        ])->assertUnprocessable()
            ->assertJsonValidationErrors('message');

        $this->assertDatabaseCount('chat_conversations', 0);
        $this->assertDatabaseCount('chat_messages', 0);
    }

    public function test_it_does_not_save_messages_when_openai_fails(): void
    {
        config(['openai.api_key' => 'test-key']);
        OpenAI::fake();

        $this->postJson('/ai-chat', [
            'message' => 'Apakah kandang saya cukup sejuk?',
        ])->assertStatus(502)
            ->assertJsonPath('success', false);

        $this->assertDatabaseCount('chat_conversations', 0);
        $this->assertDatabaseCount('chat_messages', 0);
    }
}
