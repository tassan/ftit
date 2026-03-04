<?php
$config = require __DIR__ . '/config/env.php';

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
    'nome'     => $data['nome']     ?? '',
    'negocio'  => $data['negocio']  ?? '',
    'email'    => $data['email']    ?? '',
    'telefone' => $data['telefone'] ?? '',
    'segmento' => $data['segmento'] ?? '',
    'temSite'  => $data['temSite']  ?? '',
    'dor'      => $data['dor']      ?? '',
];

$ch = curl_init($config['webhook_url']);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'x-make-apikey: ' . $config['webhook_key'],
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_exec($ch);
$ok = curl_getinfo($ch, CURLINFO_HTTP_CODE) < 400;
curl_close($ch);

echo json_encode(['ok' => $ok]);
