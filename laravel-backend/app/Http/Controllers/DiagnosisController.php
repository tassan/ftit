<?php

namespace App\Http\Controllers;

use App\Http\Requests\DiagnosisRequest;
use App\Services\DiagnosisService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class DiagnosisController extends Controller
{
    public function __construct(
        private DiagnosisService $service
    ) {
    }

    public function handle(DiagnosisRequest $request): JsonResponse
    {
        $lead      = $request->validatedPayload();
        $resultado = $this->service->generate($lead);

        if (!($resultado['success'] ?? false)) {
            return response()->json(
                ['error' => 'Erro ao gerar diagnóstico. Tente novamente.'],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return response()->json([
            'success' => true,
            'parecer' => $resultado['parecer'],
        ]);
    }
}

