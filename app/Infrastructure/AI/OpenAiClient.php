<?php

namespace App\Infrastructure\AI;

use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Arr;
use RuntimeException;

class OpenAiClient
{
    public function __construct(
        protected HttpFactory $http,
    ) {
    }

    /**
     * @param  array<int, array<string, string>>  $messages
     * @return array<string, mixed>
     */
    public function chat(array $messages, ?string $model = null): array
    {
        $apiKey = config('services.openai.api_key');
        $baseUrl = rtrim(config('services.openai.base_url', 'https://api.openai.com/v1'), '/');
        $model ??= config('services.openai.model', env('OPENAI_MODEL_DIAGNOSTICO', 'gpt-4.1-mini'));

        if (! $apiKey) {
            throw new RuntimeException('OPENAI_API_KEY is not configured');
        }

        /** @var Response $response */
        $response = $this->http->withToken($apiKey)
            ->baseUrl($baseUrl)
            ->acceptJson()
            ->asJson()
            ->post('/chat/completions', [
                'model' => $model,
                'messages' => $messages,
                'temperature' => 0.4,
                'response_format' => ['type' => 'json_object'],
            ]);

        if ($response->failed()) {
            throw new RuntimeException('OpenAI chat completion failed: '.$response->body());
        }

        return $response->json();
    }

    public function extractJsonContent(array $response): string
    {
        $content = Arr::get($response, 'choices.0.message.content');

        if (! is_string($content) || $content === '') {
            throw new RuntimeException('OpenAI response did not contain content');
        }

        return $content;
    }
}

