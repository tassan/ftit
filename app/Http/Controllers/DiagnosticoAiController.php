<?php

namespace App\Http\Controllers;

use App\Application\Diagnostico\GenerateDiagnosticoParecer;
use App\Domain\Diagnostico\DiagnosticoInput;
use App\Http\Requests\DiagnosticoRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class DiagnosticoAiController extends Controller
{
    public function __construct(
        protected GenerateDiagnosticoParecer $service,
    ) {
    }

    public function store(DiagnosticoRequest $request): JsonResponse
    {
        $data = $request->validated();

        try {
            $input = DiagnosticoInput::fromArray($data);
            $parecer = $this->service->handle($input);

            return response()->json([
                'success' => true,
                'parecer' => $parecer->toArray(),
            ]);
        } catch (\Throwable $e) {
            Log::error('Erro ao gerar parecer de diagnóstico', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Erro ao gerar parecer automático.',
            ], 500);
        }
    }
}

