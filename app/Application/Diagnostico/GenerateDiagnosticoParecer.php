<?php

namespace App\Application\Diagnostico;

use App\Domain\Diagnostico\DiagnosticoInput;
use App\Domain\Diagnostico\DiagnosticoParecer;
use App\Infrastructure\AI\OpenAiClient;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use JsonException;
use RuntimeException;

class GenerateDiagnosticoParecer
{
    public function __construct(
        protected OpenAiClient $client,
    ) {
    }

    public function handle(DiagnosticoInput $input): DiagnosticoParecer
    {
        $messages = [
            [
                'role' => 'system',
                'content' => 'Você é um consultor especialista em presença digital e automação para pequenos negócios. '
                    .'Sua resposta deve ser um JSON **válido** no formato especificado, sem texto extra.',
            ],
            [
                'role' => 'user',
                'content' => $this->buildPrompt($input),
            ],
        ];

        $rawResponse = $this->client->chat($messages);
        $content = $this->client->extractJsonContent($rawResponse);

        try {
            /** @var array<string, mixed> $data */
            $data = json_decode($content, true, flags: JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            Log::warning('Failed to decode OpenAI JSON for diagnóstico', [
                'error' => $e->getMessage(),
            ]);

            throw new RuntimeException('Failed to decode AI response JSON');
        }

        if (! isset($data['parecer']) || ! is_array($data['parecer'])) {
            // Some prompts might return the object at root
            $payload = $data;
        } else {
            /** @var array<string, mixed> $payload */
            $payload = $data['parecer'];
        }

        $parecer = DiagnosticoParecer::fromArray($payload);

        $this->dispatchWebhook($input, $parecer);

        return $parecer;
    }

    protected function buildPrompt(DiagnosticoInput $input): string
    {
        return <<<PROMPT
Gere um parecer personalizado sobre a presença digital e oportunidades de automação para o seguinte negócio:

Nome da pessoa: {$input->nome}
Nome do negócio: {$input->negocio}
Cidade: {$input->cidade}
Segmento: {$input->segmento}

Faturamento mensal estimado: {$input->faturamento}
Número de funcionários: {$input->funcionarios}

Tem site? {$input->temSite}
Está no Google Meu Negócio? {$input->googleMeuNegocio}
Instagram: {$input->instagram}
Como os clientes encontram hoje: {$input->comoAcham}

Como agenda hoje: {$input->agendamento}
Faz follow-up? {$input->followup}
Horas/semana em tarefas administrativas repetitivas: {$input->horasAdmin}

Maior problema com a presença digital hoje: {$input->problema}
O que quer resolver nos próximos 3 meses: {$input->objetivo}

Responda **somente** com um JSON válido no seguinte formato (chaves em português, sem comentários):
{
  "parecer": {
    "titulo": "string curta, específica para este negócio",
    "situacao_atual": "parágrafo explicando o cenário atual resumido, em 3–5 frases",
    "gaps": [
      { "problema": "frase curta sobre o problema", "impacto": "frase sobre impacto no negócio" }
    ],
    "potencial": "parágrafo explicando o potencial de melhoria e ganhos",
    "proximos_passos": "parágrafo sugerindo próximos passos práticos para 90 dias",
    "cta_texto": "frase convidando para uma conversa diagnóstica de 30 minutos",
    "urgencia": "alta|media|baixa"
  }
}

Não inclua nenhum texto fora do JSON.
PROMPT;
    }

    protected function dispatchWebhook(DiagnosticoInput $input, DiagnosticoParecer $parecer): void
    {
        $url = config('ftit.webhook_url');
        $key = config('ftit.webhook_key');

        if (! $url || ! $key) {
            return;
        }

        try {
            Http::withHeaders([
                'x-make-apikey' => $key,
            ])->post($url, [
                'lead' => [
                    'nome' => $input->nome,
                    'negocio' => $input->negocio,
                    'email' => $input->email,
                    'telefone' => $input->telefone,
                    'cidade' => $input->cidade,
                    'segmento' => $input->segmento,
                    'faturamento' => $input->faturamento,
                    'funcionarios' => $input->funcionarios,
                    'tem_site' => $input->temSite,
                    'google_meu_negocio' => $input->googleMeuNegocio,
                    'instagram' => $input->instagram,
                    'como_acham' => $input->comoAcham,
                    'agendamento' => $input->agendamento,
                    'followup' => $input->followup,
                    'horas_admin' => $input->horasAdmin,
                    'problema' => $input->problema,
                    'objetivo' => $input->objetivo,
                ],
                'parecer' => $parecer->toArray(),
            ]);
        } catch (\Throwable $e) {
            Log::warning('Failed to dispatch diagnóstico webhook', [
                'error' => $e->getMessage(),
            ]);
        }
    }
}

