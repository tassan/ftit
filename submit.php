<?php
if (file_exists(__DIR__ . '/.env')) {
    foreach (file(__DIR__ . '/.env') as $line) {
        $line = trim($line);
        if ($line && strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            putenv($line);
        }
    }
}

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false]);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!is_array($data)) {
    http_response_code(400);
    echo json_encode(['ok' => false]);
    exit;
}

function clean(string $val): string {
    return htmlspecialchars(strip_tags(trim($val)), ENT_QUOTES, 'UTF-8');
}

$nome     = clean($data['nome']     ?? '');
$negocio  = clean($data['negocio']  ?? '');
$segmento = clean($data['segmento'] ?? '');
$temSite  = clean($data['temSite']  ?? '');
$dor      = clean($data['dor']      ?? '');

if (!$nome || !$negocio) {
    http_response_code(400);
    echo json_encode(['ok' => false]);
    exit;
}

$to      = getenv('EMAIL_TO') ?: 'contato@ftit.com.br';
$subject = "=?UTF-8?B?" . base64_encode("Diagnóstico Express — {$nome} ({$negocio})") . "?=";
$body    = implode("\r\n", [
    "Novo diagnóstico recebido via site.",
    "",
    "Nome:     {$nome}",
    "Negócio:  {$negocio}",
    "Segmento: {$segmento}",
    "Site:     {$temSite}",
    "",
    "Principal dor:",
    $dor ?: "(não informado)",
]);
$headers = implode("\r\n", [
    "From: FTIT Site <noreply@ftit.com.br>",
    "Content-Type: text/plain; charset=UTF-8",
    "Content-Transfer-Encoding: 8bit",
]);

$sent = mail($to, $subject, $body, $headers);

echo json_encode(['ok' => $sent]);
