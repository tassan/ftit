<?php
require_once __DIR__ . '/../config/config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: ' . BASE_URL);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit(json_encode(['error' => 'Method not allowed']));
}

$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    http_response_code(400);
    exit(json_encode(['error' => 'Invalid input']));
}

// Resolve segmento: se "outro", usa o textbox
$segmento = ($input['segmento'] ?? '') === 'outro'
    ? htmlspecialchars(trim($input['segmento_outro'] ?? 'Outro'), ENT_QUOTES, 'UTF-8')
    : htmlspecialchars(trim($input['segmento'] ?? ''), ENT_QUOTES, 'UTF-8');

$campos = ['nome', 'email', 'telefone', 'cidade', 'faturamento', 'funcionarios',
           'tem_site', 'google_meu_negocio', 'instagram', 'como_acham',
           'agendamento', 'followup', 'horas_admin', 'problema', 'objetivo'];

$dados = ['segmento' => $segmento];
foreach ($campos as $campo) {
    $dados[$campo] = htmlspecialchars(trim($input[$campo] ?? ''), ENT_QUOTES, 'UTF-8');
}

$prompt    = buildPrompt($dados);
$resultado = callOpenAI($prompt);

if ($resultado['success']) {
    dispatchWebhook($dados, $resultado['parecer']);
    echo json_encode(['success' => true, 'parecer' => $resultado['parecer']]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Erro ao gerar diagnóstico. Tente novamente.']);
}

function buildPrompt(array $d): string {
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
6. Finalize com CTA personalizado e urgente para agendar uma call de 30 minutos com a FTIT
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

function callOpenAI(string $prompt): array {
    $apiKey = OPENAI_API_KEY;
    if (!$apiKey) {
        error_log('[diagnostico-ia][callOpenAI] OPENAI_API_KEY is not set');
        return ['success' => false];
    }

    $payload = json_encode([
        'model'       => getenv('OPENAI_MODEL') ?: 'gpt-5.1',
        'max_tokens'  => 1024,
        'temperature' => 0.2,
        'messages'    => [
            ['role' => 'user', 'content' => $prompt],
        ],
    ]);

    $ch = curl_init('https://api.openai.com/v1/chat/completions');
    $curlError = null;
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $payload,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_HTTPHEADER     => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $apiKey,
        ],
    ]);

    $body     = curl_exec($ch);
    if ($body === false) {
        $curlError = curl_error($ch);
    }
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200 || !$body) {
        error_log(sprintf(
            '[diagnostico-ia][callOpenAI] Non-200 response from OpenAI. status=%d curl_error=%s body=%s',
            $httpCode,
            $curlError ?: 'none',
            $body ? substr($body, 0, 500) : 'empty'
        ));
        return ['success' => false];
    }

    $response = json_decode($body, true);
    if (!is_array($response)) {
        error_log('[diagnostico-ia][callOpenAI] Failed to decode OpenAI JSON response');
        return ['success' => false];
    }

    $content  = $response['choices'][0]['message']['content'] ?? '';
    if (!$content) {
        error_log('[diagnostico-ia][callOpenAI] Missing choices[0].message.content in OpenAI response');
        return ['success' => false];
    }

    $parecer = json_decode($content, true);
    if (!$parecer) {
        error_log('[diagnostico-ia][callOpenAI] Failed to decode parecer JSON from OpenAI content: ' . substr($content, 0, 500));
        return ['success' => false];
    }

    return ['success' => true, 'parecer' => $parecer];
}

function dispatchWebhook(array $dados, array $parecer): void {
    $url = MAKE_WEBHOOK_URL;
    if (!$url) return;

    $payload = json_encode([
        'lead'      => $dados,
        'parecer'   => $parecer,
        'timestamp' => date('c'),
        'source'    => 'diagnostico-ia',
    ]);

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $payload,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 5,
        CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
    ]);
    curl_exec($ch);
    curl_close($ch);
}
