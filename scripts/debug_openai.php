<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use OpenAI\Laravel\Facades\OpenAI;

try {
    $response = OpenAI::chat()->create([
        'model' => 'gpt-5-mini',
        'messages' => [
            ['role' => 'user', 'content' => 'Buatkan satu kalimat rekomendasi sederhana.']
        ],
        'max_completion_tokens' => 100,
        'temperature' => 1,
    ]);

    var_dump($response->toArray());
} catch (Throwable $e) {
    echo 'EXCEPTION: ' . $e->getMessage() . PHP_EOL;
    echo $e->getTraceAsString();
}
