<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubmitRequest;
use App\Services\SubmitWebhookService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class SubmitController extends Controller
{
    public function __construct(
        private SubmitWebhookService $webhook
    ) {
    }

    public function handle(SubmitRequest $request): JsonResponse
    {
        $payload = $request->payload();

        $ok = $this->webhook->send($payload);

        // Legacy endpoint always returned 200 with { ok: bool }.
        return response()->json(
            ['ok' => $ok],
            Response::HTTP_OK
        );
    }
}

