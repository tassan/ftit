<?php

declare(strict_types=1);

$config = require dirname(__DIR__) . '/config/config.php';

header('Content-Type: application/json; charset=utf-8');

// Only allow POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Parse JSON body
$body = (string) file_get_contents('php://input');
$data = json_decode($body, true);

if (!is_array($data)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON body']);
    exit;
}

// Validate required fields
$required = [
    'nome', 'negocio', 'email', 'telefone', 'cidade',
    'segmento', 'faturamento', 'funcionarios',
    'tem_site', 'google_meu_negocio', 'instagram', 'como_acham',
    'agendamento', 'followup', 'horas_admin',
];

foreach ($required as $field) {
    if (empty($data[$field])) {
        http_response_code(422);
        echo json_encode(['error' => "Campo obrigatório ausente: $field"]);
        exit;
    }
}

/**
 * Sanitize a string value: strip tags and trim whitespace.
 */
function clean(?string $value): string
{
    return trim((string) strip_tags((string) $value));
}

/**
 * Truncate a string to a maximum byte length.
 */
function truncate(string $value, int $max): string
{
    return mb_substr($value, 0, $max);
}

// Normalize segmento
$segmento      = clean($data['segmento'] ?? '');
$segmentoOutro = clean($data['segmento_outro'] ?? '');
if ($segmento === 'outro' && $segmentoOutro !== '') {
    $segmento = $segmentoOutro;
}

// Build sanitized input
$input = [
    'nome'             => truncate(clean($data['nome']             ?? ''), 120),
    'negocio'          => truncate(clean($data['negocio']          ?? ''), 160),
    'email'            => truncate(clean($data['email']            ?? ''), 160),
    'telefone'         => truncate(clean($data['telefone']         ?? ''), 40),
    'cidade'           => truncate(clean($data['cidade']           ?? ''), 160),
    'segmento'         => truncate($segmento,                              160),
    'faturamento'      => truncate(clean($data['faturamento']      ?? ''), 80),
    'funcionarios'     => truncate(clean($data['funcionarios']     ?? ''), 80),
    'tem_site'         => truncate(clean($data['tem_site']         ?? ''), 120),
    'google_meu_negocio'=> truncate(clean($data['google_meu_negocio'] ?? ''), 120),
    'instagram'        => truncate(clean($data['instagram']        ?? ''), 120),
    'como_acham'       => truncate(clean($data['como_acham']       ?? ''), 120),
    'agendamento'      => truncate(clean($data['agendamento']      ?? ''), 120),
    'followup'         => truncate(clean($data['followup']         ?? ''), 120),
    'horas_admin'      => truncate(clean($data['horas_admin']      ?? ''), 120),
    'problema'         => truncate(clean($data['problema']         ?? ''), 2000),
    'objetivo'         => truncate(clean($data['objetivo']         ?? ''), 2000),
];

// Build OpenAI prompt
$prompt = <<<PROMPT
Gere um parecer personalizado sobre a presença digital e oportunidades de automação para o seguinte negócio:

Nome da pessoa: {$input['nome']}
Nome do negócio: {$input['negocio']}
Cidade: {$input['cidade']}
Segmento: {$input['segmento']}

Faturamento mensal estimado: {$input['faturamento']}
Número de funcionários: {$input['funcionarios']}

Tem site? {$input['tem_site']}
Está no Google Meu Negócio? {$input['google_meu_negocio']}
Instagram: {$input['instagram']}
Como os clientes encontram hoje: {$input['como_acham']}

Como agenda hoje: {$input['agendamento']}
Faz follow-up? {$input['followup']}
Horas/semana em tarefas administrativas repetitivas: {$input['horas_admin']}

Maior problema com a presença digital hoje: {$input['problema']}
O que quer resolver nos próximos 3 meses: {$input['objetivo']}

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

$messages = [
    [
        'role'    => 'system',
        'content' => 'Você é um consultor especialista em presença digital e automação para pequenos negócios. '
                   . 'Sua resposta deve ser um JSON **válido** no formato especificado, sem texto extra.',
    ],
    [
        'role'    => 'user',
        'content' => $prompt,
    ],
];

// Call OpenAI
$apiKey   = (string) ($config['openai_key'] ?? '');
$model    = (string) ($config['openai_model'] ?? 'gpt-4.1-mini');
$baseUrl  = 'https://api.openai.com/v1';

if ($apiKey === '') {
    http_response_code(500);
    echo json_encode(['error' => 'OPENAI_API_KEY não configurada.']);
    exit;
}

$payload = json_encode([
    'model'           => $model,
    'messages'        => $messages,
    'temperature'     => 0.4,
    'response_format' => ['type' => 'json_object'],
]);

$ch = curl_init($baseUrl . '/chat/completions');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => $payload,
    CURLOPT_HTTPHEADER     => [
        'Authorization: Bearer ' . $apiKey,
        'Content-Type: application/json',
        'Accept: application/json',
    ],
    CURLOPT_TIMEOUT        => 60,
]);

$rawResponse = curl_exec($ch);
$curlError   = curl_error($ch);
$httpCode    = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($curlError !== '') {
    error_log('OpenAI cURL error: ' . $curlError);
    http_response_code(500);
    echo json_encode(['error' => 'Erro de comunicação com a IA.']);
    exit;
}

$aiResponse = json_decode((string) $rawResponse, true);

if (!is_array($aiResponse) || $httpCode !== 200) {
    error_log('OpenAI API error (' . $httpCode . '): ' . $rawResponse);
    http_response_code(500);
    echo json_encode(['error' => 'Erro ao chamar a IA.']);
    exit;
}

$content = $aiResponse['choices'][0]['message']['content'] ?? '';

if (!is_string($content) || $content === '') {
    error_log('OpenAI empty content: ' . $rawResponse);
    http_response_code(500);
    echo json_encode(['error' => 'Resposta vazia da IA.']);
    exit;
}

try {
    $parsed = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
} catch (\JsonException $e) {
    error_log('OpenAI JSON parse error: ' . $e->getMessage() . ' | content: ' . $content);
    http_response_code(500);
    echo json_encode(['error' => 'Falha ao interpretar resposta da IA.']);
    exit;
}

// Support both { "parecer": {...} } and the object at root
$payload = isset($parsed['parecer']) && is_array($parsed['parecer'])
    ? $parsed['parecer']
    : $parsed;

// Validate and sanitize parecer fields
$urgenciaRaw = trim((string) ($payload['urgencia'] ?? ''));
$urgencia    = in_array($urgenciaRaw, ['alta', 'media', 'baixa'], true) ? $urgenciaRaw : '';

$gaps = [];
foreach (($payload['gaps'] ?? []) as $gap) {
    if (is_array($gap)) {
        $gaps[] = [
            'problema' => trim((string) ($gap['problema'] ?? '')),
            'impacto'  => trim((string) ($gap['impacto']  ?? '')),
        ];
    }
}

$parecer = [
    'titulo'          => trim((string) ($payload['titulo']          ?? '')),
    'situacao_atual'  => trim((string) ($payload['situacao_atual']  ?? '')),
    'gaps'            => $gaps,
    'potencial'       => trim((string) ($payload['potencial']       ?? '')),
    'proximos_passos' => trim((string) ($payload['proximos_passos'] ?? '')),
    'cta_texto'       => trim((string) ($payload['cta_texto']       ?? '')),
    'urgencia'        => $urgencia,
];

// Dispatch webhook (non-blocking, best-effort)
$webhookUrl = (string) ($config['webhook_url'] ?? '');
$webhookKey = (string) ($config['webhook_key'] ?? '');

if ($webhookUrl !== '' && $webhookKey !== '') {
    $webhookPayload = json_encode([
        'lead'   => [
            'nome'              => $input['nome'],
            'negocio'           => $input['negocio'],
            'email'             => $input['email'],
            'telefone'          => $input['telefone'],
            'cidade'            => $input['cidade'],
            'segmento'          => $input['segmento'],
            'faturamento'       => $input['faturamento'],
            'funcionarios'      => $input['funcionarios'],
            'tem_site'          => $input['tem_site'],
            'google_meu_negocio'=> $input['google_meu_negocio'],
            'instagram'         => $input['instagram'],
            'como_acham'        => $input['como_acham'],
            'agendamento'       => $input['agendamento'],
            'followup'          => $input['followup'],
            'horas_admin'       => $input['horas_admin'],
            'problema'          => $input['problema'],
            'objetivo'          => $input['objetivo'],
        ],
        'parecer' => $parecer,
    ]);

    $wh = curl_init($webhookUrl);
    curl_setopt_array($wh, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $webhookPayload,
        CURLOPT_HTTPHEADER     => [
            'x-make-apikey: ' . $webhookKey,
            'Content-Type: application/json',
        ],
        CURLOPT_TIMEOUT        => 10,
    ]);
    $whResult = curl_exec($wh);
    $whErr    = curl_error($wh);
    curl_close($wh);

    if ($whErr !== '') {
        error_log('Webhook dispatch error: ' . $whErr);
    }
}

echo json_encode(['success' => true, 'parecer' => $parecer]);
