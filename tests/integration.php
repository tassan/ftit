<?php
/**
 * Simple integration test runner for FTIT.
 *
 * Usage:
 *   TEST_BASE_URL=http://localhost php tests/integration.php
 *
 * - TEST_BASE_URL should point to the web root served by Apache (where `/`, `/diagnostico`, etc. resolve).
 * - If OPENAI_API_KEY is not set in the web app environment, the AI diagnosis test will be marked as SKIPPED.
 */

$baseUrl = rtrim(getenv('TEST_BASE_URL') ?: 'http://localhost', '/');

$tests = [];

// ───────────────────────────── Helpers ─────────────────────────────

function httpRequest(string $method, string $url, ?array $jsonBody = null): array {
    $ch = curl_init($url);
    $headers = [];

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT        => 30,
    ]);

    $method = strtoupper($method);
    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        if ($jsonBody !== null) {
            $payload  = json_encode($jsonBody);
            $headers[] = 'Content-Type: application/json';
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        }
    } elseif ($method !== 'GET') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    }

    if ($headers) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }

    $body   = curl_exec($ch);
    $error  = $body === false ? curl_error($ch) : null;
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE) ?: 0;
    curl_close($ch);

    return [
        'status' => $status,
        'body'   => $body,
        'error'  => $error,
    ];
}

function assertTrue(bool $cond, string $message, string $name): array {
    if ($cond) {
        return ['name' => $name, 'status' => 'pass', 'message' => $message];
    }
    return ['name' => $name, 'status' => 'fail', 'message' => $message];
}

function decodeJson(string $body): ?array {
    $data = json_decode($body, true);
    return is_array($data) ? $data : null;
}

// ─────────────────────── Page / route tests ────────────────────────

$tests[] = function () use ($baseUrl) {
    $name = 'GET /';
    $res  = httpRequest('GET', $baseUrl . '/');
    if ($res['error']) {
        return ['name' => $name, 'status' => 'fail', 'message' => "cURL error: {$res['error']}"];
    }
    if ($res['status'] !== 200) {
        return ['name' => $name, 'status' => 'fail', 'message' => "Expected HTTP 200, got {$res['status']}"];
    }
    return assertTrue(
        stripos($res['body'] ?? '', 'FTIT') !== false,
        'Home page contains brand name',
        $name
    );
};

$tests[] = function () use ($baseUrl) {
    $name = 'GET /diagnostico';
    $res  = httpRequest('GET', $baseUrl . '/diagnostico');
    if ($res['error']) {
        return ['name' => $name, 'status' => 'fail', 'message' => "cURL error: {$res['error']}"];
    }
    if ($res['status'] !== 200) {
        return ['name' => $name, 'status' => 'fail', 'message' => "Expected HTTP 200, got {$res['status']}"];
    }
    return assertTrue(
        stripos($res['body'] ?? '', 'Diagnóstico digital') !== false
        || stripos($res['body'] ?? '', 'diagnóstico') !== false,
        'Diagnosis page loads with expected copy',
        $name
    );
};

$tests[] = function () use ($baseUrl) {
    $name = 'GET /privacidade';
    $res  = httpRequest('GET', $baseUrl . '/privacidade');
    if ($res['error']) {
        return ['name' => $name, 'status' => 'fail', 'message' => "cURL error: {$res['error']}"];
    }
    if ($res['status'] !== 200) {
        return ['name' => $name, 'status' => 'fail', 'message' => "Expected HTTP 200, got {$res['status']}"];
    }
    return ['name' => $name, 'status' => 'pass', 'message' => 'Privacy page responds with 200'];
};

$tests[] = function () use ($baseUrl) {
    $name = 'GET /obrigado';
    $res  = httpRequest('GET', $baseUrl . '/obrigado');
    if ($res['error']) {
        return ['name' => $name, 'status' => 'fail', 'message' => "cURL error: {$res['error']}"];
    }
    if ($res['status'] !== 200) {
        return ['name' => $name, 'status' => 'fail', 'message' => "Expected HTTP 200, got {$res['status']}"];
    }
    return ['name' => $name, 'status' => 'pass', 'message' => 'Thank-you page responds with 200'];
};

// ───────────────────────── API: submit.php ─────────────────────────

$tests[] = function () use ($baseUrl) {
    $name = 'POST /api/submit.php';

    $payload = [
        'nome'     => 'Teste Integração',
        'negocio'  => 'FTIT Test',
        'email'    => 'teste@example.com',
        'telefone' => '5511999999999',
        'segmento' => 'Clínica estética / Beleza',
        'temSite'  => 'Sim',
        'dor'      => 'Poucos leads qualificados',
    ];

    $res = httpRequest('POST', $baseUrl . '/api/submit.php', $payload);

    if ($res['error']) {
        return ['name' => $name, 'status' => 'fail', 'message' => "cURL error: {$res['error']}"];
    }

    if ($res['status'] < 200 || $res['status'] >= 300) {
        return ['name' => $name, 'status' => 'fail', 'message' => "Expected 2xx, got {$res['status']}"];
    }

    $data = decodeJson($res['body'] ?? '');
    if ($data === null) {
        return ['name' => $name, 'status' => 'fail', 'message' => 'Response is not valid JSON'];
    }

    if (!array_key_exists('ok', $data)) {
        return ['name' => $name, 'status' => 'fail', 'message' => "JSON missing 'ok' key"];
    }

    if (!is_bool($data['ok'])) {
        return ['name' => $name, 'status' => 'fail', 'message' => "'ok' key is not boolean"];
    }

    return ['name' => $name, 'status' => 'pass', 'message' => 'submit.php responds with JSON { ok: bool }'];
};

// ──────────────── API: diagnostico-ia.php (OpenAI) ─────────────────

$tests[] = function () use ($baseUrl) {
    $name = 'POST /api/diagnostico-ia.php';

    // Only run this test if the AI key is configured; otherwise, mark as skipped.
    if (!getenv('OPENAI_API_KEY')) {
        return ['name' => $name, 'status' => 'skip', 'message' => 'OPENAI_API_KEY not set; skipping AI integration test'];
    }

    $payload = [
        'nome'            => 'Teste Integração',
        'email'           => 'teste@example.com',
        'telefone'        => '5511999999999',
        'cidade'          => 'São Paulo',
        'segmento'        => 'Clínica estética / Beleza',
        'segmento_outro'  => '',
        'faturamento'     => 'R$ 10k–30k',
        'funcionarios'    => '1–5',
        'tem_site'        => 'Sim',
        'google_meu_negocio' => 'Sim',
        'instagram'       => 'Sim',
        'como_acham'      => 'Indicação',
        'agendamento'     => 'WhatsApp manual',
        'followup'        => 'Sim',
        'horas_admin'     => '5–10h',
        'problema'        => 'Poucos clientes vindo do digital',
        'objetivo'        => 'Dobrar o faturamento em 6 meses',
    ];

    $res = httpRequest('POST', $baseUrl . '/api/diagnostico-ia.php', $payload);

    if ($res['error']) {
        return ['name' => $name, 'status' => 'fail', 'message' => "cURL error: {$res['error']}"];
    }

    if ($res['status'] !== 200) {
        return ['name' => $name, 'status' => 'fail', 'message' => "Expected HTTP 200, got {$res['status']}"];
    }

    $data = decodeJson($res['body'] ?? '');
    if ($data === null) {
        return ['name' => $name, 'status' => 'fail', 'message' => 'Response is not valid JSON'];
    }

    if (empty($data['success']) || !isset($data['parecer']) || !is_array($data['parecer'])) {
        return ['name' => $name, 'status' => 'fail', 'message' => 'Missing or invalid { success, parecer } structure'];
    }

    $p = $data['parecer'];
    $requiredKeys = ['titulo', 'situacao_atual', 'gaps', 'potencial', 'proximos_passos', 'cta_texto', 'urgencia'];
    foreach ($requiredKeys as $key) {
        if (!array_key_exists($key, $p)) {
            return ['name' => $name, 'status' => 'fail', 'message' => "parecer missing key: {$key}"];
        }
    }

    return ['name' => $name, 'status' => 'pass', 'message' => 'diagnostico-ia.php returns expected parecer schema'];
};

// ────────────────────────── Runner output ───────────────────────────

$results = ['pass' => 0, 'fail' => 0, 'skip' => 0];
$details = [];

foreach ($tests as $test) {
    $result = $test();
    $status = $result['status'];
    $name   = $result['name'] ?? 'unnamed';
    $msg    = $result['message'] ?? '';

    if (!isset($results[$status])) {
        $results[$status] = 0;
    }
    $results[$status]++;

    $details[] = sprintf(
        "[%s] %s - %s",
        strtoupper($status),
        $name,
        $msg
    );
}

foreach ($details as $line) {
    echo $line, PHP_EOL;
}

echo PHP_EOL;
echo sprintf(
    "Summary: %d passed, %d failed, %d skipped\n",
    $results['pass'],
    $results['fail'],
    $results['skip']
);

exit($results['fail'] > 0 ? 1 : 0);

