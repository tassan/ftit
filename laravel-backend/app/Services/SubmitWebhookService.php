<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class SubmitWebhookService
{
    /**
     * Send the submit payload to the Make webhook.
     */
    public function send(array $payload): bool
    {
        $url = Config::get('services.make_webhook.url');
        $key = Config::get('services.make_webhook.key');

        if (!$url || !$key) {
            // Mirror legacy behavior: if we can't send, return false.
            return false;
        }

        $response = Http::withHeaders([
                'Content-Type'  => 'application/json',
                'x-make-apikey' => $key,
            ])
            ->asJson()
            ->post($url, $payload);

        return $response->status() < 400;
    }
}

