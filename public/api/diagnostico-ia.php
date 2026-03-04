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

$campos = ['nome', 'email', 'segmento', 'cidade', 'faturamento', 'funcionarios',
           'tem_site', 'google_meu_negocio', 'como_acham', 'problema', 'objetivo'];

$dados = [];
foreach ($campos as $campo) {
    $dados[$campo] = htmlspecialchars(trim($input[$campo] ?? ''), ENT_QUOTES, 'UTF-8');
}

$prompt = buildPrompt($dados);
$resultado = callAnthropic($prompt);

if ($resultado['success']) {
    dispatchWebhook($dados, $resultado['parecer']);
    echo json_encode(['success' => true, 'parecer' => $resultado['parecer']]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Erro ao gerar diagnóstico. Tente novamente.']);
}

function buildPrompt(array $d): string {
    return <<<EOT
Você é um consultor sênior da FTIT, especializado em transformação digital para pequenas empresas no Brasil.

Analise os dados deste lead e gere um diagnóstico digital personalizado. Seu objetivo é demonstrar expertise real e criar desejo genuíno pelo serviço — sem pressão, sem exagero.

DADOS DO LEAD:
- Nome/Empresa: {$d['nome']}
- Segmento: {$d['segmento']}
- Cidade: {$d['cidade']}
- Faturamento mensal estimado: {$d['faturamento']}
- Número de funcionários: {$d['funcionarios']}
- Tem site? {$d['tem_site']}
- Está no Google Meu Negócio? {$d['google_meu_negocio']}
- Como clientes encontram o negócio hoje: {$d['como_acham']}
- Maior problema digital hoje: {$d['problema']}
- Objetivo nos próximos 3 meses: {$d['objetivo']}

REGRAS:
1. Comece validando o contexto — reconheça o que o negócio tem ou faz bem antes de apontar gaps
2. Identifique 2 a 3 gaps concretos com impacto direto em faturamento ou captação de clientes
3. Use dados reais quando possível (ex: "76% dos consumidores pesquisam online antes de contratar um serviço local")
4. Aponte os próximos passos naturalmente em direção aos serviços da FTIT (site estratégico, automação de processos)
5. Finalize com um CTA personalizado e urgente para agendar uma call de 30 minutos
6. Tom: direto, especialista, humano. Fale como consultor experiente, não como chatbot

Responda APENAS com JSON válido, sem markdown, sem texto antes ou depois:
{
  "titulo": "string",
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

function callAnthropic(string $prompt): array {
    $apiKey = ANTHROPIC_API_KEY;
    if (!$apiKey) return ['success' => false];

    $payload = json_encode([
        'model'      => 'claude-sonnet-4-6',
        'max_tokens' => 1024,
        'messages'   => [['role' => 'user', 'content' => $prompt]]
    ]);

    $ch = curl_init('https://api.anthropic.com/v1/messages');
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $payload,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_HTTPHEADER     => [
            'Content-Type: application/json',
            'x-api-key: ' . $apiKey,
            'anthropic-version: 2023-06-01',
        ],
    ]);

    $body     = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200 || !$body) return ['success' => false];

    $response = json_decode($body, true);
    $text     = $response['content'][0]['text'] ?? '';
    $parecer  = json_decode($text, true);

    if (!$parecer) return ['success' => false];

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
