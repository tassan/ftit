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

$payload = [
    'name'    => $data['nome']     ?? '',
    'company' => $data['negocio']  ?? '',
    'area'    => $data['segmento'] ?? '',
    'site'    => $data['temSite']  ?? '',
    'pain'    => $data['dor']      ?? '',
];

$ch = curl_init('https://hook.us2.make.com/hpegxiczzk1fvmwzecbsc2rqtjdhyodf');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . getenv('WEBHOOK_API_KEY'),
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_exec($ch);
$ok = curl_getinfo($ch, CURLINFO_HTTP_CODE) < 400;
curl_close($ch);

echo json_encode(['ok' => $ok]);
