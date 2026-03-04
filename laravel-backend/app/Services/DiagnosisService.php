<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Psr\Log\LoggerInterface;

class DiagnosisService
{
    public function __construct(
        private LoggerInterface $logger
    ) {
    }

    /**
     * Call OpenAI to generate the diagnosis and optionally dispatch the webhook.
     *
     * @param  array{nome:string,segmento:string,cidade:string,faturamento:string,funcionarios:string,tem_site:string,google_meu_negocio:string,instagram:string,como_acham:string,agendamento:string,followup:string,horas_admin:string,problema:string,objetivo:string}  $lead
     */
    public function generate(array $lead): array
    {
        $prompt = $this->buildPrompt($lead);

        $openaiKey   = Config::get('services.openai.api_key');
        $openaiModel = Config::get('services.openai.model', 'gpt-5.1');

        if (!$openaiKey) {
            $this->logger->error('[diagnostico-ia][DiagnosisService] OPENAI_API_KEY is not set');
            return ['success' => false];
        }

        $response = Http::withToken($openaiKey)
            ->timeout(30)
            ->post('https://api.openai.com/v1/chat/completions', [
                'model'       => $openaiModel,
                'max_tokens'  => 1024,
                'temperature' => 0.2,
                'messages'    => [
                    ['role' => 'user', 'content' => $prompt],
                ],
            ]);

        if (!$response->successful()) {
            $this->logger->error('[diagnostico-ia][DiagnosisService] Non-200 response from OpenAI', [
                'status' => $response->status(),
                'body'   => substr($response->body(), 0, 500),
            ]);
            return ['success' => false];
        }

        $content = Arr::get($response->json(), 'choices.0.message.content', '');
        if (!$content) {
            $this->logger->error('[diagnostico-ia][DiagnosisService] Missing choices[0].message.content');
            return ['success' => false];
        }

        $parecer = json_decode($content, true);
        if (!is_array($parecer)) {
            $this->logger->error('[diagnostico-ia][DiagnosisService] Failed to decode parecer JSON', [
                'content_preview' => substr($content, 0, 500),
            ]);
            return ['success' => false];
        }

        $this->dispatchWebhook($lead, $parecer);

        return ['success' => true, 'parecer' => $parecer];
    }

    private function buildPrompt(array $d): string
    {
        return <<<EOT
Você é um consultor sênior da FTIT, especializado em transformação digital para pequenos negócios no Brasil.

Analise os dados deste lead e gere um diagnóstico digital personalizado. Seu objetivo é demonstrar expertise real e criar desejo genuíno pelo serviço — sem pressão, sem exagero.

DADOS DO LEAD:
- Nome/Empresa: {$d['nome']}
- Segmento: {$d['segmento']}
- Cidade: {$d['cidade']}
- Faturamento mensal estimado: {$d['faturamento']}
- Número de funcionários: {$d['funcionarios']}
- Tem site? {$d['tem_site']}
- Está no Google Meu Negócio? {$d['google_meu_negocio']}
- Tem Instagram ativo? {$d['instagram']}
- Como clientes encontram o negócio: {$d['como_acham']}
- Como agenda atendimentos: {$d['agendamento']}
- Faz acompanhamento pós-atendimento? {$d['followup']}
- Horas semanais em tarefas administrativas: {$d['horas_admin']}
- Maior problema digital hoje: {$d['problema']}
- Objetivo nos próximos 3 meses: {$d['objetivo']}

REGRAS:
1. Comece validando o contexto — reconheça o que o negócio tem ou faz bem
2. Identifique 2 a 3 gaps concretos com impacto direto em faturamento ou captação
3. Use dados reais do mercado brasileiro quando possível
4. Se o negócio agenda manualmente ou gasta muitas horas em tarefas repetitivas, destaque o potencial de automação
5. Aponte próximos passos em direção natural aos serviços da FTIT: site estratégico e/ou automação de processos
6. Finalize com CTA personalizado e urgente para agendar a call de 30 minutos com a FTIT
7. Tom: direto, especialista, humano — consultor experiente, não chatbot

Responda APENAS com JSON válido, sem markdown, sem texto antes ou depois:
{
  "titulo": "string — ex: Diagnóstico Digital — [Nome do negócio]",
  "situacao_atual": "string — 2 a 3 frases contextualizando o negócio",
  "gaps": [
    { "problema": "string", "impacto": "string — com número ou dado quando possível" }
  ],
  "potencial": "string — o que o negócio pode ganhar resolvendo esses gaps",
  "proximos_passos": "string — recomendação que aponta pro serviço FTIT adequado",
  "cta_texto": "string — frase personalizada para agendar a call",
  "urgencia": "alta|media|baixa"
}
EOT;
    }

    private function dispatchWebhook(array $lead, array $parecer): void
    {
        $url = Config::get('services.make_webhook.url');
        if (!$url) {
            return;
        }

        $payload = [
            'lead'      => $lead,
            'parecer'   => $parecer,
            'timestamp' => now()->toIso8601String(),
            'source'    => Config::get('services.make_webhook.source', 'diagnostico-ia'),
        ];

        Http::timeout(5)
            ->withHeaders([
                'Content-Type' => 'application/json',
            ])
            ->post($url, $payload);
    }
}

